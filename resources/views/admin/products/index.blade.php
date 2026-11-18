@extends('layouts.admin')
@section('title','Kelola Produk')
@section('content')


<div class="flex between"><h1>Kelola Produk</h1><a class="btn" href="{{ route('admin.products.create') }}">+ Tambah Produk</a></div>
<table><thead><tr><th>Foto</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
@foreach($products as $p)<tr><td>@if($p->image)<img class="thumb-admin" src="{{ str_starts_with($p->image, 'http') ? $p->image : asset($p->image) }}" alt="Foto {{ $p->name }}">@else <span class="muted">-</span> @endif</td><td>{{ $p->name }}</td><td>{{ $p->category->name ?? '-' }}</td><td>Rp {{ number_format($p->price,0,',','.') }}</td><td>{{ $p->stock }}</td><td>{{ $p->is_active ? 'Aktif':'Nonaktif' }}</td>
<td class="flex"><a class="btn small secondary" href="{{ route('admin.products.edit',$p) }}">Ubah</a>
<form method="POST" action="{{ route('admin.products.destroy',$p) }}" onsubmit="return confirm('Hapus produk ini?')">@csrf @method('DELETE')<button class="btn small danger">Hapus</button></form></td></tr>@endforeach
</tbody></table><div style="margin-top:14px">{{ $products->links() }}</div>
@endsection
