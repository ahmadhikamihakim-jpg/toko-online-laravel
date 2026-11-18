<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MidtransNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['midtrans.server_key' => 'test-server-key']);
    }

    private function makeOrder(): Order
    {
        $user = User::factory()->create();
        return Order::create([
            'user_id' => $user->id,
            'code' => 'INV-TEST-001',
            'recipient_name' => 'Budi',
            'phone' => '08123456789',
            'address' => 'Jl. Test No. 1',
            'payment_method' => 'midtrans',
            'status' => 'pending',
            'total' => 698000,
        ]);
    }

    private function payload(Order $order, string $status, ?string $signature = null): array
    {
        $gross = number_format((float) $order->total, 2, '.', '');
        $data = [
            'order_id' => $order->code,
            'status_code' => '200',
            'gross_amount' => $gross,
            'transaction_status' => $status,
            'payment_type' => 'bank_transfer',
        ];
        $data['signature_key'] = $signature ?? hash('sha512', $order->code.'200'.$gross.'test-server-key');
        return $data;
    }

    public function test_settlement_notification_marks_order_paid(): void
    {
        $order = $this->makeOrder();
        $this->postJson('/midtrans/notification', $this->payload($order, 'settlement'))
            ->assertOk();
        $order->refresh();
        $this->assertSame('paid', $order->status);
        $this->assertSame('settlement', $order->payment_status);
        $this->assertNotNull($order->paid_at);
    }

    public function test_invalid_signature_is_rejected(): void
    {
        $order = $this->makeOrder();
        $this->postJson('/midtrans/notification', $this->payload($order, 'settlement', 'salah'))
            ->assertForbidden();
        $this->assertSame('pending', $order->refresh()->status);
    }

    public function test_expire_notification_cancels_order(): void
    {
        $order = $this->makeOrder();
        $this->postJson('/midtrans/notification', $this->payload($order, 'expire'))
            ->assertOk();
        $this->assertSame('cancelled', $order->refresh()->status);
    }
}
