<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request, MidtransService $midtrans)
    {
        $payload = $request->all();

        $order = Order::where('code', $payload['order_id'] ?? '')->first();
        if (! $order) {
            return response()->json(['message' => 'Pesanan tidak ditemukan.'], 404);
        }

        if (! $midtrans->signatureValid($payload)) {
            return response()->json(['message' => 'Signature tidak valid.'], 403);
        }

        // Jumlah yang dibayar harus sama dengan total pesanan.
        $expectedAmount = number_format((float) $order->total, 2, '.', '');
        if (($payload['gross_amount'] ?? null) !== $expectedAmount) {
            return response()->json(['message' => 'Jumlah pembayaran tidak cocok.'], 422);
        }

        $transactionStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus = (string) ($payload['fraud_status'] ?? 'accept');

        DB::transaction(function () use ($order, $payload, $transactionStatus, $fraudStatus) {
            $order = Order::whereKey($order->id)->lockForUpdate()->first();
            $previousStatus = $order->status;

            $order->payment_status = $transactionStatus;
            $order->payment_type = $payload['payment_type'] ?? $order->payment_type;

            $isPaid = $transactionStatus === 'settlement'
                || ($transactionStatus === 'capture' && $fraudStatus === 'accept');

            if ($isPaid && $previousStatus !== 'paid') {
                $order->status = 'paid';
                $order->paid_at = now();
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'], true)
                && ! in_array($previousStatus, ['paid', 'cancelled'], true)) {
                $order->status = 'cancelled';
                // Kembalikan stok barang untuk pesanan yang batal/ kedaluwarsa.
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        $item->product()->increment('stock', $item->quantity);
                    }
                }
            }

            $order->save();
        });

        return response()->json(['message' => 'OK']);
    }
}
