@extends('layouts.admin')
@section('title','Profil')
@section('content')
<div class="card" style="margin-bottom:18px">
  <div class="profile-head">
    <span class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
    <div>
      <h2 style="margin:0">{{ $user->name }}</h2>
      <div class="profile-meta">
        <span class="chip">Administrator</span>
        <span class="chip">Admin Terverifikasi</span>
      </div>
      <p class="muted" style="margin:8px 0 0">
        Email <strong>{{ $user->email }}</strong> &nbsp;•&nbsp; Peran <strong>Pengelola Toko</strong> &nbsp;•&nbsp; Zona Waktu <strong>Asia/Jakarta (WIB)</strong> &nbsp;•&nbsp; Terdaftar <strong>{{ $user->created_at?->format('d M Y') }}</strong>
      </p>
      @if($user->bio)<p style="margin:6px 0 0">{{ $user->bio }}</p>@endif
    </div>
  </div>
</div>

<div class="card" style="margin-bottom:18px">
  <h2 style="margin-top:0">Pengaturan Profil</h2>
  <p class="muted">Perbarui profil akun dan detail kontakmu.</p>
  <form method="POST" action="{{ route('admin.profile.update') }}">@csrf @method('PUT')
    <label for="name">Nama</label><input id="name" name="name" value="{{ old('name', $user->name) }}" required>
    <label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
    <label for="bio">Bio</label><textarea id="bio" name="bio" rows="3" maxlength="500" placeholder="Ceritakan singkat tentang dirimu...">{{ old('bio', $user->bio) }}</textarea>
    <button class="btn" type="submit">Simpan Profil</button>
  </form>
</div>

<div class="card">
  <h2 style="margin-top:0">Ubah Kata Sandi</h2>
  <form method="POST" action="{{ route('admin.profile.password') }}">@csrf @method('PUT')
    <label for="current_password">Kata Sandi Saat Ini</label><input id="current_password" type="password" name="current_password" required>
    <label for="password">Kata Sandi Baru (min. 8 karakter)</label><input id="password" type="password" name="password" required>
    <label for="password_confirmation">Ulangi Kata Sandi Baru</label><input id="password_confirmation" type="password" name="password_confirmation" required>
    <button class="btn" type="submit">Ubah Kata Sandi</button>
  </form>
</div>
@endsection
