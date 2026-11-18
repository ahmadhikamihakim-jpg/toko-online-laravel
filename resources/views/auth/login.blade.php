@extends('layouts.app')
@section('title','Masuk')
@section('content')
<div class="card" style="max-width:480px;margin:0 auto"><h1>Masuk</h1>
<form method="POST" action="{{ route('login') }}">@csrf
<label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
<label for="password">Kata Sandi</label><input id="password" type="password" name="password" required>
<label style="font-weight:400"><input type="checkbox" name="remember" style="width:auto"> Ingat saya</label><br>
<button class="btn" type="submit">Masuk</button></form>
<p class="muted">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
<p class="muted">Demo admin: admin@toko.test / password<br>Demo pelanggan: budi@toko.test / password</p></div>
@endsection
