@extends('layouts.admin')
@section('title','Dashboard Admin')
@section('content')
<div class="stat-grid">
  <div class="stat-card biru"><span class="stat-ic">▣</span><div><h3>{{ $stats['products'] }}</h3><p>Total Produk</p></div></div>
  <div class="stat-card teal"><span class="stat-ic">▤</span><div><h3>{{ $stats['categories'] }}</h3><p>Total Kategori</p></div></div>
  <div class="stat-card oranye"><span class="stat-ic">🧾</span><div><h3>{{ $stats['orders'] }}</h3><p>Total Pesanan</p></div></div>
  <div class="stat-card ungu"><span class="stat-ic">👥</span><div><h3>{{ $stats['users'] }}</h3><p>Total Pengguna</p></div></div>
</div>
<div class="stat-card pendapatan">
  <div style="display:flex;gap:12px;align-items:center"><span class="stat-ic">💰</span><div><h3>Rp {{ number_format($stats['revenue'],0,',','.') }}</h3><p>Total Pendapatan (pesanan tidak dibatalkan)</p></div></div>
</div>
<h2 style="margin-top:24px">Pesanan Terbaru</h2>
@if($latestOrders->isEmpty())<div class="card">Belum ada pesanan.</div>@else
<table><thead><tr><th>Kode</th><th>Pelanggan</th><th>Total</th><th>Status</th><th></th></tr></thead><tbody>
@foreach($latestOrders as $o)<tr><td>{{ $o->code }}</td><td>{{ $o->user->name ?? '-' }}</td><td>Rp {{ number_format($o->total,0,',','.') }}</td><td><span class="chip">{{ $o->statusLabel() }}</span></td><td><a href="{{ route('admin.orders.show',$o) }}">Detail</a></td></tr>@endforeach
</tbody></table>@endif
@endsection
