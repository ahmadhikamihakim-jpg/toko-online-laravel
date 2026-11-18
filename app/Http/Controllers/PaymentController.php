<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show(Order $order, MidtransService $midtrans, Request $request)
    {
        abort_unless($order->user_id === auth()->id() || (auth()->user()?->is_admin), 403);
        abort_unless($order->payment_method === 'midtrans', 404);

        if ($order->status === 'paid') {
            return redirect()->route('orders.show', $order)->with('success', 'Pesanan ini sudah dibayar.');
        }

        $error = null;
        if ($midtrans->configured() && ! $order->snap_token) {
            try {
                $midtrans->createSnapToken($order);
                $order->refresh();
            } catch (\Throwable $e) {
                $error = $e->getMessage();
            }
        }

        return view('checkout.pay', [
            'order' => $order->load('items'),
            'configured' => $midtrans->configured(),
            'snapJsUrl' => $midtrans->snapJsUrl(),
            'clientKey' => (string) config('midtrans.client_key'),
            'error' => $error ?? $request->session()->get('error'),
        ]);
    }
}
