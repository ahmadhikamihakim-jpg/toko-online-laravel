@extends('layouts.admin')
@section('title','Kelola Kategori')
@section('content')


<div class="flex between"><h1>Kelola Kategori</h1><a class="btn" href="{{ route('admin.categories.create') }}">+ Tambah Kategori</a></div>
<table><thead><tr><th>Nama</th><th>Slug</th><th>Jumlah Produk</th><th>Aksi</th></tr></thead><tbody>
@foreach($categories as $c)<tr><td>{{ $c->name }}</td><td>{{ $c->slug }}</td><td>{{ $c->products_count }}</td>
<td class="flex"><a class="btn small secondary" href="{{ route('admin.categories.edit',$c) }}">Ubah</a>
<form method="POST" action="{{ route('admin.categories.destroy',$c) }}" onsubmit="return confirm('Hapus kategori ini? Produk di dalamnya ikut terhapus.')">@csrf @method('DELETE')<button class="btn small danger">Hapus</button></form></td></tr>@endforeach
</tbody></table><div style="margin-top:14px">{{ $categories->links() }}</div>
@endsection
