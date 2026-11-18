@extends('layouts.app')
@section('title','Daftar')
@section('content')
<div class="card" style="max-width:480px;margin:0 auto"><h1>Daftar Akun Baru</h1>
<form method="POST" action="{{ route('register') }}">@csrf
<label for="name">Nama Lengkap</label><input id="name" name="name" value="{{ old('name') }}" required autofocus>
<label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" required>
<label for="password">Kata Sandi (min. 8 karakter)</label><input id="password" type="password" name="password" required>
<label for="password_confirmation">Konfirmasi Kata Sandi</label><input id="password_confirmation" type="password" name="password_confirmation" required>
<button class="btn" type="submit">Daftar</button></form>
<p class="muted">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p></div>
@endsection
