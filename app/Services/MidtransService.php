<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MidtransService
{
    public function configured(): bool
    {
        return ! empty(config('midtrans.server_key')) && ! empty(config('midtrans.client_key'));
    }

    public function snapJsUrl(): string
    {
        return config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    protected function snapApiUrl(): string
    {
        return config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    /**
     * Minta Snap token ke Midtrans untuk pesanan ini dan simpan di pesanan.
     */
    public function createSnapToken(Order $order): string
    {
        if (! $this->configured()) {
            throw new RuntimeException('Pembayaran Midtrans belum dikonfigurasi (kunci API belum diisi).');
        }

        $order->loadMissing('items');

        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'id' => (string) ($item->product_id ?? $item->id),
                'price' => (int) $item->price,
                'quantity' => (int) $item->quantity,
                'name' => mb_substr($item->product_name, 0, 50),
            ];
        }

        $payload = [
            'transaction_details' => [
                'order_id' => $order->code,
                'gross_amount' => (int) $order->total,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $order->recipient_name,
                'phone' => $order->phone,
                'billing_address' => ['address' => $order->address],
                'shipping_address' => [
                    'first_name' => $order->recipient_name,
                    'phone' => $order->phone,
                    'address' => $order->address,
                ],
            ],
        ];

        $response = Http::withBasicAuth((string) config('midtrans.server_key'), '')
            ->acceptJson()
            ->asJson()
            ->post($this->snapApiUrl(), $payload);

        if ($response->failed() || empty($response->json('token'))) {
            $message = $response->json('error_messages.0') ?? $response->json('status_message') ?? 'Midtrans menolak permintaan token.';
            throw new RuntimeException('Gagal membuat pembayaran Midtrans: '.$message);
        }

        $token = (string) $response->json('token');
        $order->forceFill(['snap_token' => $token])->save();

        return $token;
    }

    /**
     * Verifikasi signature_key dari notifikasi Midtrans:
     * sha512(order_id + status_code + gross_amount + server_key)
     */
    public function signatureValid(array $payload): bool
    {
        $serverKey = (string) config('midtrans.server_key');
        if ($serverKey === '' || empty($payload['signature_key'])) {
            return false;
        }
        $expected = hash('sha512',
            ($payload['order_id'] ?? '').
            ($payload['status_code'] ?? '').
            ($payload['gross_amount'] ?? '').
            $serverKey
        );

        return hash_equals($expected, (string) $payload['signature_key']);
    }
}
