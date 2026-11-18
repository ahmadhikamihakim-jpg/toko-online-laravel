@extends('layouts.app')
@section('title','Pesanan Saya')
@section('content')
<h1>Pesanan Saya</h1>
@if($orders->isEmpty())<div class="card">Anda belum punya pesanan. <a href="{{ route('home') }}">Mulai belanja</a></div>
@else<table><thead><tr><th>Kode</th><th>Tanggal</th><th>Total</th><th>Pembayaran</th><th>Status</th><th></th></tr></thead><tbody>
@foreach($orders as $o)<tr><td>{{ $o->code }}</td><td>{{ $o->created_at->format('d M Y H:i') }}</td><td>Rp {{ number_format($o->total,0,',','.') }}</td><td>{{ $o->paymentLabel() }}</td><td><span class="chip">{{ $o->statusLabel() }}</span></td><td><a class="btn small" href="{{ route('orders.show',$o) }}">Detail</a></td></tr>@endforeach
</tbody></table><div style="margin-top:14px">{{ $orders->links() }}</div>@endif
@endsection
