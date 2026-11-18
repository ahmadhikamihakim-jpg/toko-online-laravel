@extends('layouts.admin')
@section('title','Tambah Produk')
@section('content')


<h1>Tambah Produk</h1><form method="POST" enctype="multipart/form-data" action="{{ route('admin.products.store') }}" class="card">@csrf @include('admin.products._form')<button class="btn">Simpan</button></form>
@endsection
