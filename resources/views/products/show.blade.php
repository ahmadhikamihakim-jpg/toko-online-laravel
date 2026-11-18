@extends('layouts.app')
@section('title', $product->name)
@section('content')
<div class="card">
  <div class="flex" style="align-items:flex-start">
    <div style="flex:1;min-width:260px">@if($product->image)<img class="detail" src="{{ str_starts_with($product->image, 'http') ? $product->image : asset($product->image) }}" alt="{{ $product->name }}">@endif</div>
    <div style="flex:1;min-width:260px">
      <span class="chip">{{ $product->category->name ?? '-' }}</span>
      <h1>{{ $product->name }}</h1>
      <p class="price" style="font-size:24px">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
      <p>{{ $product->description }}</p>
      <p class="muted">Stok tersedia: {{ $product->stock }}</p>
      @if($product->stock > 0)
      <form method="POST" action="{{ route('cart.add', $product) }}">
        @csrf <label for="quantity">Jumlah</label><input id="quantity" type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" style="max-width:120px">
        <button class="btn" type="submit">Tambah ke Keranjang</button>
      </form>
      @else <div class="alert error">Maaf, stok produk ini sedang habis.</div> @endif
    </div>
  </div>
</div>
@if($related->isNotEmpty())
<h2>Produk Terkait</h2>
<div class="grid">@foreach($related as $p)
  <div class="card product">@if($p->image)<img src="{{ str_starts_with($p->image, 'http') ? $p->image : asset($p->image) }}" alt="{{ $p->name }}">@endif
  <h3><a href="{{ route('products.show', $p) }}">{{ $p->name }}</a></h3>
  <p class="price">Rp {{ number_format($p->price, 0, ',', '.') }}</p></div>
@endforeach</div>@endif
@endsection
