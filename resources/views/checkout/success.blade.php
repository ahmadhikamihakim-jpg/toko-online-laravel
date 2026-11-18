@extends('layouts.app')
@section('title','Pesanan Berhasil')
@section('content')
<div class="card"><h1>✅ Pesanan Berhasil Dibuat!</h1>
<p>Terima kasih sudah berbelanja. Kode pesanan Anda: <strong>{{ $order->code }}</strong></p>
<p>Status: <span class="chip">{{ $order->statusLabel() }}</span> &nbsp; Pembayaran: {{ $order->paymentLabel() }}</p>
<p>Total bayar: <strong class="price">Rp {{ number_format($order->total,0,',','.') }}</strong></p>
@if($order->payment_method=='transfer')<p class="muted">Silakan transfer ke rekening BCA 1234567890 a.n. Toko Online, lalu pesanan akan kami proses setelah pembayaran terkonfirmasi.</p>@else<p class="muted">Siapkan uang tunai sebesar total bayar saat paket tiba di alamat Anda.</p>@endif
<p><a class="btn" href="{{ route('orders.show', $order) }}">Lihat Detail Pesanan</a> <a class="btn secondary" href="{{ route('home') }}">Lanjut Belanja</a></p></div>
@endsection
