@extends('layouts.admin')
@section('title','Detail Pesanan Admin')
@section('content')


<h1>Pesanan {{ $order->code }}</h1>
<div class="card">
<p><strong>Pelanggan:</strong> {{ $order->user->name ?? '-' }} ({{ $order->user->email ?? '-' }})<br>
<strong>Penerima:</strong> {{ $order->recipient_name }} ({{ $order->phone }})<br><strong>Alamat:</strong> {{ $order->address }}<br>
<strong>Pembayaran:</strong> {{ $order->paymentLabel() }} | <strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
<table><thead><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr></thead><tbody>
@foreach($order->items as $it)<tr><td>{{ $it->product_name }}</td><td>Rp {{ number_format($it->price,0,',','.') }}</td><td>{{ $it->quantity }}</td><td>Rp {{ number_format($it->subtotal,0,',','.') }}</td></tr>@endforeach
</tbody></table>
<p style="text-align:right"><strong>Total: Rp {{ number_format($order->total,0,',','.') }}</strong></p>
<form method="POST" action="{{ route('admin.orders.update',$order) }}" class="flex">@csrf @method('PATCH')
<label for="status">Ubah Status</label><select id="status" name="status" style="max-width:260px">@foreach(\App\Models\Order::STATUSES as $k=>$v)<option value="{{ $k }}" @selected($order->status==$k)>{{ $v }}</option>@endforeach</select>
<button class="btn">Simpan Status</button></form>
</div>
@endsection
