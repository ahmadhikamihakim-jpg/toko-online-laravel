@extends('layouts.app')
@section('title','Checkout')
@section('content')
<h1>Checkout</h1>
<div class="card" style="margin-bottom:16px"><h3>Ringkasan Belanja</h3>
<table><tbody>@foreach($cart as $item)<tr><td>{{ $item['name'] }} x {{ $item['quantity'] }}</td><td style="text-align:right">Rp {{ number_format($item['price']*$item['quantity'],0,',','.') }}</td></tr>@endforeach
<tr><td><strong>Total Bayar</strong></td><td style="text-align:right"><strong>Rp {{ number_format($total,0,',','.') }}</strong></td></tr></tbody></table></div>
<form method="POST" action="{{ route('checkout.store') }}" class="card">@csrf
<label for="recipient_name">Nama Penerima</label><input id="recipient_name" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}" required>
<label for="phone">Nomor Telepon / WhatsApp</label><input id="phone" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx">
<label for="address">Alamat Lengkap Pengiriman</label><textarea id="address" name="address" rows="4" required>{{ old('address') }}</textarea>
<label for="payment_method">Metode Pembayaran</label>
<select id="payment_method" name="payment_method"><option value="transfer" @selected(old('payment_method')=='transfer')>Transfer Bank</option><option value="cod" @selected(old('payment_method')=='cod')>Bayar di Tempat (COD)</option><option value="midtrans" @selected(old('payment_method')=='midtrans')>Pembayaran Online (Midtrans) — kartu, e-wallet, VA bank</option></select>
<p class="muted" style="margin-top:4px">Pembayaran online diproses aman lewat Midtrans.@if(!($midtransConfigured ?? false)) <strong>Catatan:</strong> Midtrans di toko ini belum diaktifkan pemilik toko (kunci API belum diisi), tetapi pesanan tetap dapat dibuat dan dibayar menyusul dari halaman pesanan setelah diaktifkan.@endif</p>
<button class="btn" type="submit">Buat Pesanan</button>
</form>
@endsection
