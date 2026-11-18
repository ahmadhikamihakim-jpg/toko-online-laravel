@extends('layouts.admin')
@section('title','Kelola Pesanan')
@section('content')


<h1>Kelola Pesanan</h1>
<form method="GET" class="flex" style="margin-bottom:12px"><select name="status" style="max-width:240px"><option value="">Semua Status</option>@foreach(\App\Models\Order::STATUSES as $k=>$v)<option value="{{ $k }}" @selected(request('status')==$k)>{{ $v }}</option>@endforeach</select><button class="btn small">Filter</button></form>
@if($orders->isEmpty())<div class="card">Belum ada pesanan.</div>@else
<table><thead><tr><th>Kode</th><th>Tanggal</th><th>Pelanggan</th><th>Total</th><th>Pembayaran</th><th>Status</th><th></th></tr></thead><tbody>
@foreach($orders as $o)<tr><td>{{ $o->code }}</td><td>{{ $o->created_at->format('d M Y H:i') }}</td><td>{{ $o->user->name ?? '-' }}</td><td>Rp {{ number_format($o->total,0,',','.') }}</td><td>{{ $o->paymentLabel() }}</td><td><span class="chip">{{ $o->statusLabel() }}</span></td><td><a class="btn small" href="{{ route('admin.orders.show',$o) }}">Detail</a></td></tr>@endforeach
</tbody></table><div style="margin-top:14px">{{ $orders->links() }}</div>@endif
@endsection
