@extends('layouts.app')
@section('title','Beranda')
@section('content')
@if(!request('q') && !request('kategori'))
<section class="hero">
  <div>
    <h1>Belanja Seru,<br><span class="hl">Gaya Makin Seru.</span></h1>
    <p>Temukan koleksi pilihan kami — elektronik, fashion, kebutuhan rumah, sampai hobi — dengan kualitas terbaik dan harga bersahabat.</p>
    <div class="flex">
      <a class="btn" href="#katalog">Belanja Sekarang</a>
      <a class="btn secondary" href="#buletin">Dapatkan Promo</a>
    </div>
  </div>
  <div class="thumb"><img src="{{ asset('images/products/sepatu-lari-airflex-running.jpg') }}" alt="Produk unggulan Toko Online"></div>
</section>
@endif

<h1 id="katalog" style="font-size:26px;font-weight:800">Katalog Produk</h1>
<p class="muted">Cari produk atau saring berdasarkan kategori.</p>
<form method="GET" action="{{ route('home') }}" class="card" style="margin-bottom:18px">
  <div class="flex">
    <div style="flex:2;min-width:200px"><label for="q">Cari produk</label><input id="q" name="q" value="{{ request('q') }}" placeholder="Contoh: batik, laptop, sepatu..."></div>
    <div style="flex:1;min-width:160px"><label for="kategori">Kategori</label>
      <select id="kategori" name="kategori"><option value="">Semua Kategori</option>
      @foreach($categories as $c)<option value="{{ $c->slug }}" @selected(request('kategori')==$c->slug)>{{ $c->name }} ({{ $c->products_count }})</option>@endforeach</select></div>
    <div><button class="btn" type="submit">Cari</button> <a class="btn secondary" href="{{ route('home') }}">Atur Ulang</a></div>
  </div>
</form>
<div class="catbar">
  <a href="{{ route('home') }}" class="{{ !request('kategori') ? 'on' : '' }}">Semua</a>
  @foreach($categories as $c)<a href="{{ route('home', ['kategori' => $c->slug]) }}" class="{{ request('kategori')==$c->slug ? 'on' : '' }}">{{ $c->name }}</a>@endforeach
</div>
@if($products->isEmpty()) <div class="card">Produk tidak ditemukan. Coba kata kunci atau kategori lain.</div> @endif
<div class="grid">
@foreach($products as $p)
  <div class="card product">
    @if($p->image)<a class="thumb" href="{{ route('products.show', $p) }}"><img src="{{ str_starts_with($p->image, 'http') ? $p->image : asset($p->image) }}" alt="{{ $p->name }}"></a>@endif
    <p style="margin:12px 0 0"><span class="chip">{{ $p->category->name ?? '-' }}</span></p>
    <h3 style="margin:6px 0"><a href="{{ route('products.show', $p) }}">{{ $p->name }}</a></h3>
    <p class="price" style="margin:2px 0">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
    <p class="muted" style="margin:2px 0">Stok: {{ $p->stock }}</p>
    <div class="spacer"></div>
    @if($p->stock > 0)
      <form method="POST" action="{{ route('cart.add', $p) }}" style="margin-top:10px">@csrf <button class="btn block" type="submit">+ Keranjang</button></form>
    @else <p style="margin-top:10px"><span class="chip out">Stok Habis</span></p> @endif
    <a class="btn secondary block" style="margin-top:8px" href="{{ route('products.show', $p) }}">Lihat Detail</a>
  </div>
@endforeach
</div>
<div style="margin-top:18px">{{ $products->links() }}</div>

<section class="newsletter" id="buletin">
  <h2>Tetap Terhubung</h2>
  <p>Berlangganan buletin kami untuk penawaran eksklusif, produk terbaru, dan inspirasi gaya setiap minggunya.</p>
  <form method="POST" action="{{ route('newsletter') }}">@csrf
    <input type="email" name="email" required placeholder="Alamat email kamu">
    <button class="btn" type="submit">Berlangganan</button>
  </form>
</section>
@endsection
