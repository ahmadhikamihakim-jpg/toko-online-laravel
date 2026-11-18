@extends('layouts.app')
@section('title','Detail Pesanan '.$order->code)
@section('content')
<h1>Detail Pesanan {{ $order->code }}</h1>
<div class="card">
<p>Status: <span class="chip">{{ $order->statusLabel() }}</span> | Pembayaran: {{ $order->paymentLabel() }} | Tanggal: {{ $order->created_at->format('d M Y H:i') }}</p>
<p><strong>Penerima:</strong> {{ $order->recipient_name }} ({{ $order->phone }})<br><strong>Alamat:</strong> {{ $order->address }}</p>
<table><thead><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr></thead><tbody>
@foreach($order->items as $it)<tr><td>{{ $it->product_name }}</td><td>Rp {{ number_format($it->price,0,',','.') }}</td><td>{{ $it->quantity }}</td><td>Rp {{ number_format($it->subtotal,0,',','.') }}</td></tr>@endforeach
</tbody></table>
@if($order->payment_method=='midtrans' && $order->status=='pending')<p><a class="btn" href="{{ route('payment.show', $order) }}">Bayar Sekarang via Midtrans</a></p>@endif
<p style="text-align:right"><strong>Total: Rp {{ number_format($order->total,0,',','.') }}</strong></p>
</div>
@endsection
