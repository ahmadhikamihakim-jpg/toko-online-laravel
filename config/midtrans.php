<?php

return [
    // Isi kunci ini dari Dashboard Midtrans (Settings > Access Keys).
    // Kosongkan = pembayaran Midtrans belum aktif; transfer & COD tetap jalan.
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
    'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),
];
