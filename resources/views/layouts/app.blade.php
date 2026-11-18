<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Toko Online') - Toko Online</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="announce">Gratis ongkir untuk pesanan di atas Rp 500.000 &nbsp;•&nbsp; Promo minggu ini: diskon sampai 30% untuk produk pilihan</div>
<header class="site"><div class="container bar">
  <a href="{{ route('home') }}" class="logo">Toko Online<span class="dot">.</span></a>
  <nav class="main">
    <a href="{{ route('home') }}">Belanja</a>
    <a href="{{ route('cart.index') }}">Keranjang ({{ \App\Http\Controllers\CartController::count() }})</a>
    @auth
      @unless(auth()->user()->is_admin) <a href="{{ route('orders.index') }}">Pesanan Saya</a> @endunless
      @if(auth()->user()->is_admin) <a href="{{ route('admin.dashboard') }}">Admin</a> @endif
      <span class="who">Halo, {{ auth()->user()->name }}</span>
      <form method="POST" action="{{ route('logout') }}" style="display:inline">@csrf <button class="btn small" type="submit">Keluar</button></form>
    @else
      <a href="{{ route('login') }}">Masuk</a>
      <a class="btn small" href="{{ route('register') }}">Daftar</a>
    @endauth
  </nav>
</div></header>
<main class="container">
  @if(session('success')) <div class="alert success">{{ session('success') }}</div> @endif
  @if(session('error')) <div class="alert error">{{ session('error') }}</div> @endif
  @if($errors->any()) <div class="errors"><strong>Terjadi kesalahan:</strong><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div> @endif
  @yield('content')
</main>
<footer class="site"><div class="container">
  <div class="cols">
    <div>
      <span class="brand">Toko Online<span class="dot">.</span></span>
      <p>Produk pilihan berkualitas untuk gaya hidupmu. Kerajinan dan kualitas modern, harga bersahabat, dan pengiriman ke seluruh Indonesia.</p>
      <p>Jakarta, Indonesia<br>halo@tokoonline.test<br>0812-3456-7890</p>
    </div>
    <div><h4>Belanja</h4>
      <a href="{{ route('home') }}">Semua Produk</a>
      <a href="{{ route('cart.index') }}">Keranjang</a>
      @auth @unless(auth()->user()->is_admin) <a href="{{ route('orders.index') }}">Pesanan Saya</a> @endunless @endauth
    </div>
    <div><h4>Bantuan</h4>
      <a href="{{ route('home') }}">Cara Belanja</a>
      <a href="{{ route('home') }}">Info Pengiriman</a>
      <a href="{{ route('home') }}">Pengembalian &amp; Penukaran</a>
    </div>
    <div><h4>Akun</h4>
      <a href="{{ route('login') }}">Masuk</a>
      <a href="{{ route('register') }}">Daftar</a>
      @auth @unless(auth()->user()->is_admin) <a href="{{ route('orders.index') }}">Lacak Pesanan</a> @endunless @endauth
    </div>
  </div>
  <div class="fine">Toko Online &copy; {{ date('Y') }} — Belanja mudah, aman, dan terpercaya.</div>
</div></footer>
</body></html>
