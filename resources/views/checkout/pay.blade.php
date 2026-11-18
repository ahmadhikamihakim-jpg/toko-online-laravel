@extends('layouts.app')
@section('title','Pembayaran Midtrans')
@section('content')
<div class="card">
<h1>Pembayaran Online — {{ $order->code }}</h1>
<p>Status: <span class="chip">{{ $order->statusLabel() }}</span> &nbsp; Pembayaran: {{ $order->paymentLabel() }}</p>
<p>Total bayar: <strong class="price">Rp {{ number_format($order->total,0,',','.') }}</strong></p>

@if($error)
<p style="color:#b91c1c"><strong>Pembayaran belum bisa diproses:</strong> {{ $error }}</p>
@endif

@if(!$configured)
<p class="muted">Pembayaran Midtrans di toko ini <strong>belum aktif</strong> karena pemilik toko belum mengisi kunci API Midtrans di konfigurasi server. Pesananmu sudah tersimpan dengan status <strong>Menunggu Pembayaran</strong> — setelah Midtrans diaktifkan, kamu bisa membayar dari halaman <a href="{{ route('orders.show', $order) }}">detail pesanan</a> tanpa perlu membuat pesanan baru.</p>
@elseif($order->snap_token)
<p class="muted">Klik tombol di bawah untuk membayar dengan kartu kredit/debit, e-wallet, atau virtual account bank lewat jendela aman Midtrans.</p>
<p><button class="btn" id="pay-button" type="button">Bayar Sekarang</button>
<a class="btn secondary" href="{{ route('orders.show', $order) }}">Bayar Nanti</a></p>
<script src="{{ $snapJsUrl }}" data-client-key="{{ $clientKey }}"></script>
<script>
document.getElementById('pay-button').addEventListener('click', function () {
    window.snap.pay('{{ $order->snap_token }}', {
        onSuccess: function () { window.location.href = '{{ route('orders.show', $order) }}'; },
        onPending: function () { window.location.href = '{{ route('orders.show', $order) }}'; },
        onError: function () { alert('Pembayaran gagal diproses. Silakan coba lagi.'); },
        onClose: function () { alert('Jendela pembayaran ditutup sebelum pembayaran selesai. Kamu bisa melanjutkan dari halaman detail pesanan.'); }
    });
});
</script>
@else
<p class="muted">Token pembayaran belum tersedia. Muat ulang halaman ini untuk mencoba lagi, atau hubungi penjual bila masalah berlanjut.</p>
@endif
</div>
@endsection
