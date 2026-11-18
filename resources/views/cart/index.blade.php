@extends('layouts.app')
@section('title','Keranjang Belanja')
@section('content')
<h1>Keranjang Belanja</h1>
@if(empty($cart)) <div class="card">Keranjang Anda masih kosong. <a href="{{ route('home') }}">Mulai belanja</a></div>
@else
<form method="POST" action="{{ route('cart.update') }}">@csrf @method('PATCH')
<table><thead><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th>Aksi</th></tr></thead><tbody>
@foreach($cart as $item)
<tr><td><a href="{{ route('products.show', $item['slug']) }}">{{ $item['name'] }}</a></td>
<td>Rp {{ number_format($item['price'],0,',','.') }}</td>
<td><input type="number" name="quantities[{{ $item['id'] }}]" value="{{ $item['quantity'] }}" min="0" style="width:80px"></td>
<td>Rp {{ number_format($item['price']*$item['quantity'],0,',','.') }}</td>
<td><button class="btn small danger" type="submit" form="remove-{{ $item['id'] }}">Hapus</button></td></tr>
@endforeach
</tbody></table>
<div class="flex between" style="margin-top:14px"><strong>Total: Rp {{ number_format($total,0,',','.') }}</strong>
<span><button class="btn secondary" type="submit">Perbarui Keranjang</button> <a class="btn" href="{{ route('checkout.index') }}">Checkout</a></span></div>
</form>
@foreach($cart as $item)<form id="remove-{{ $item['id'] }}" method="POST" action="{{ route('cart.remove', $item['slug']) }}">@csrf @method('DELETE')</form>@endforeach
<form method="POST" action="{{ route('cart.clear') }}" style="margin-top:10px">@csrf @method('DELETE')<button class="btn small danger" type="submit">Kosongkan Keranjang</button></form>
@endif
@endsection
