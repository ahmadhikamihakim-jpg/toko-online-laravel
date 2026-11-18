@extends('layouts.admin')
@section('title','Ubah Produk')
@section('content')


<h1>Ubah Produk</h1><form method="POST" enctype="multipart/form-data" action="{{ route('admin.products.update',$product) }}" class="card">@csrf @method('PUT') @include('admin.products._form')<button class="btn">Perbarui</button></form>
@endsection
