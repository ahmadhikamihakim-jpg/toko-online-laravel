<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Admin') - Admin Toko Online</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="admin-body">
<div class="admin-shell">
  <aside class="admin-sidebar">
    <div class="sidebar-brand">Toko Online<span class="dot">.</span> <small>ADMIN</small></div>
    <div class="sidebar-user">
      <span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
      <div><strong>{{ auth()->user()->name }}</strong><span class="status"><i></i> Aktif — Administrator</span></div>
    </div>
    <nav class="sidebar-menu">
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}">▦ Dashboard</a>
      <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'on' : '' }}">▤ Kategori</a>
      <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'on' : '' }}">▣ Produk</a>
      <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'on' : '' }}">🧾 Pesanan</a>
      <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'on' : '' }}">👥 Pengguna</a>
      <a href="{{ route('admin.profile.edit') }}" class="{{ request()->routeIs('admin.profile.*') ? 'on' : '' }}">👤 Profil</a>
      <span class="menu-sep"></span>
      <a href="{{ route('home') }}">🏠 Lihat Toko</a>
      <form method="POST" action="{{ route('logout') }}">@csrf <button type="submit" class="menu-btn">↩ Keluar</button></form>
    </nav>
    <div class="sidebar-foot">Panel pengelola toko<br>Toko Online &copy; {{ date('Y') }}</div>
  </aside>
  <div class="admin-main">
    <header class="admin-topbar">
      <h1>@yield('title', 'Dashboard')</h1>
      <div class="top-user"><span class="muted">{{ now()->translatedFormat('l, d F Y') }}</span><span class="avatar sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span> <strong>{{ auth()->user()->name }}</strong></div>
    </header>
    <main class="admin-content">
      @if(session('success')) <div class="alert success">{{ session('success') }}</div> @endif
      @if(session('error')) <div class="alert error">{{ session('error') }}</div> @endif
      @if($errors->any()) <div class="errors"><strong>Terjadi kesalahan:</strong><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div> @endif
      @yield('content')
    </main>
  </div>
</div>
</body></html>
