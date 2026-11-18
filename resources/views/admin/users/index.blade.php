@extends('layouts.admin')
@section('title','Pengguna Terdaftar')
@section('content')
<h1>Pengguna Terdaftar</h1>
<p class="muted">Total {{ $totalUsers }} pengguna sudah mendaftar di toko ini.</p>
<form method="GET" class="flex" style="margin-bottom:12px">
  <input name="q" value="{{ request('q') }}" placeholder="Cari nama atau email..." style="max-width:320px;margin:0">
  <button class="btn small" type="submit">Cari</button>
  @if(request('q'))<a class="btn small secondary" href="{{ route('admin.users.index') }}">Atur Ulang</a>@endif
</form>
@if($users->isEmpty())<div class="card">Tidak ada pengguna yang cocok.</div>@else
<table><thead><tr><th>Nama</th><th>Email</th><th>Peran</th><th>Jumlah Pesanan</th><th>Terdaftar Sejak</th></tr></thead><tbody>
@foreach($users as $u)<tr>
<td>{{ $u->name }}</td><td>{{ $u->email }}</td>
<td>@if($u->is_admin)<span class="chip">Admin</span>@else Pelanggan @endif</td>
<td>{{ $u->orders_count }}</td>
<td>{{ $u->created_at?->format('d M Y, H:i') }}</td>
</tr>@endforeach
</tbody></table><div style="margin-top:14px">{{ $users->links() }}</div>@endif
@endsection
