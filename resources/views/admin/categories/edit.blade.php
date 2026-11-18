@extends('layouts.admin')
@section('title','Ubah Kategori')
@section('content')


<h1>Ubah Kategori</h1><form method="POST" action="{{ route('admin.categories.update',$category) }}" class="card">@csrf @method('PUT') @include('admin.categories._form')<button class="btn">Perbarui</button></form>
@endsection
