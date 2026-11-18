<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Data dummy TAMBAHAN untuk belajar dan mencoba aplikasi.
 *
 * Aman dijalankan berulang kali (memakai updateOrCreate/firstOrCreate,
 * jadi tidak menggandakan data). Jalankan dengan:
 *
 *     php artisan db:seed --class=DummyDataSeeder
 *
 * Isi: kategori & produk tambahan, 8 pelanggan contoh, dan 10 pesanan
 * contoh dengan status bermacam-macam. Kata sandi semua akun contoh: password
 */
class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan data dasar (admin, pelanggan demo, 12 produk awal) sudah ada.
        $this->call(DatabaseSeeder::class);

        // ---------- Kategori & produk tambahan ----------
        $data = [
            'Elektronik' => [
                ['Mouse Wireless Logitech Style', 149000, 45, 'Mouse nirkabel senyap dengan penerima USB nano, baterai awet hingga 12 bulan.'],
                ['Kipas Angin Meja Mini USB', 99000, 0, 'Kipas meja mini bertenaga USB, 3 tingkat kecepatan. Stok habis = contoh produk tidak tersedia.'],
            ],
            'Pakaian' => [
                ['Hoodie Pria Fleece Tebal', 179000, 30, 'Hoodie bahan fleece tebal dan hangat, kantong depan dan tali serut.'],
                ['Dress Wanita Motif Bunga', 229000, 16, 'Dress midi motif bunga bahan rayon jatuh, adem dan nyaman untuk acara santai.'],
            ],
            'Sepatu & Tas' => [
                ['Dompet Kulit Pria Lipat', 139000, 50, 'Dompet lipat kulit sintetis premium dengan 8 slot kartu dan pelindung RFID.'],
                ['Tas Selempang Wanita Mini', 189000, 21, 'Tas selempang mini bahan kulit sintetis, muat ponsel, dompet, dan makeup kecil.'],
            ],
            'Rumah Tangga' => [
                ['Lampu Meja LED Belajar', 119000, 33, 'Lampu meja LED dengan 3 warna cahaya dan lengan fleksibel, colokan USB.'],
                ['Keset Kamar Mandi Anti Slip', 49000, 80, 'Keset microfiber daya serap tinggi dengan alas anti slip.'],
            ],
            'Kecantikan & Perawatan' => [
                ['Serum Wajah Vitamin C 20ml', 89000, 60, 'Serum vitamin C untuk membantu mencerahkan kulit, cocok untuk semua jenis kulit.'],
                ['Sunscreen SPF 50 PA+++ 30g', 69000, 75, 'Tabir surya ringan tidak lengket, perlindungan SPF 50 untuk aktivitas luar ruangan.'],
                ['Lipstik Matte Tahan Lama', 59000, 90, 'Lipstik matte pigmented, tidak membuat bibir kering, tersedia banyak pilihan warna.'],
                ['Sabun Cuci Muka Gentle 100ml', 39000, 120, 'Pembersih wajah lembut pH seimbang untuk pemakaian sehari-hari.'],
                ['Hair Tonic Penumbuh Rambut 100ml', 79000, 25, 'Tonic rambut dengan ginseng untuk membantu merawat rambut rontok.'],
            ],
            'Olahraga & Outdoor' => [
                ['Matras Yoga Anti Slip 6mm', 129000, 40, 'Matras yoga ketebalan 6mm bahan TPE anti slip, termasuk tas jinjing.'],
                ['Botol Minum Sport 1 Liter', 59000, 100, 'Botol minum BPA-free 1 liter dengan penanda waktu dan pegangan anti selip.'],
                ['Tenda Camping 4 Orang', 599000, 7, 'Tenda dome kapasitas 4 orang, rangka fiberglass dan bahan tahan gerimis.'],
                ['Sepeda Lipat 20 Inci', 2199000, 5, 'Sepeda lipat rangka alloy 20 inci, 7 percepatan, mudah disimpan di bagasi.'],
                ['Sarung Tangan Gym Fitness', 69000, 55, 'Sarung tangan latihan dengan pelindung telapak dan wrist strap.'],
            ],
            'Makanan & Minuman' => [
                ['Kopi Bubuk Arabika 200g', 65000, 85, 'Biji kopi arabika sangrai medium dari petani Gayo, digiling segar saat dipesan.'],
                ['Madu Murni Hutan 500ml', 119000, 35, 'Madu hutan murni tanpa tambahan gula, dipanen berkelanjutan.'],
                ['Keripik Singkong Balado 250g', 25000, 150, 'Keripik singkong renyah bumbu balado pedas manis, kemasan ziplock.'],
                ['Teh Melati Celup Isi 25', 18000, 200, 'Teh melati celup aroma wangi alami, isi 25 kantong.'],
                ['Sambal Bawang Botol 150g', 29000, 110, 'Sambal bawang pedas gurih dari cabai segar, tanpa pengawet.'],
            ],
            'Mainan & Hobi' => [
                ['Puzzle Kayu Edukasi Anak', 79000, 42, 'Puzzle kayu warna-warni untuk melatih motorik dan pengenalan bentuk anak.'],
                ['Gitar Akustik Pemula', 549000, 9, 'Gitar akustik senar nilon ukuran 38 inci, cocok untuk pemula, termasuk pick dan tas.'],
                ['Cat Air Set 24 Warna', 89000, 38, 'Cat air 24 warna dengan kuas dan palet, untuk hobi melukis dan tugas sekolah.'],
                ['Rubik 3x3 Speed Cube', 45000, 95, 'Rubik 3x3 putaran halus anti macet untuk latihan speedcubing.'],
                ['Boneka Beruang Jumbo 80cm', 199000, 13, 'Boneka beruang besar 80 cm bahan plush lembut, cocok untuk hadiah.'],
            ],
        ];

        foreach ($data as $catName => $products) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($catName)],
                ['name' => $catName, 'description' => 'Kategori contoh untuk data dummy.']
            );
            foreach ($products as [$name, $price, $stock, $desc]) {
                Product::updateOrCreate(['slug' => Str::slug($name)], [
                    'category_id' => $category->id, 'name' => $name, 'description' => $desc,
                    'price' => $price, 'stock' => $stock,
                    'image' => 'images/products/'.Str::slug($name).'.jpg',
                    'is_active' => true,
                ]);
            }
        }

        // ---------- Pelanggan contoh ----------
        $customers = [
            ['Siti Rahayu', 'siti@toko.test'], ['Agus Salim', 'agus@toko.test'],
            ['Dewi Lestari', 'dewi@toko.test'], ['Rina Marlina', 'rina@toko.test'],
            ['Joko Prasetyo', 'joko@toko.test'], ['Putri Ayu', 'putri@toko.test'],
            ['Dedi Supriadi', 'dedi@toko.test'], ['Maya Kartika', 'maya@toko.test'],
        ];
        $users = [];
        foreach ($customers as [$name, $email]) {
            $users[$email] = User::updateOrCreate(['email' => $email], [
                'name' => $name, 'password' => 'password', 'is_admin' => false,
            ]);
        }
        $budi = User::where('email', 'budi@toko.test')->first();

        // ---------- Pesanan contoh ----------
        // [kode, pelanggan, status, pembayaran, hari lalu, [[slug produk, jumlah], ...]]
        $orders = [
            ['INV-DUMMY-001', $budi, 'paid', 'transfer', 12, [['smartphone-andromax-x5-8256gb', 1]]],
            ['INV-DUMMY-002', $users['siti@toko.test'], 'shipped', 'midtrans', 9, [['kemeja-batik-pria-megamendung', 2], ['kaos-polos-katun-premium', 1]]],
            ['INV-DUMMY-003', $users['agus@toko.test'], 'completed', 'cod', 20, [['sepatu-lari-airflex-running', 1]]],
            ['INV-DUMMY-004', $users['dewi@toko.test'], 'pending', 'midtrans', 1, [['serum-wajah-vitamin-c-20ml', 2], ['sunscreen-spf-50-pa-30g', 1]]],
            ['INV-DUMMY-005', $users['rina@toko.test'], 'paid', 'midtrans', 6, [['matras-yoga-anti-slip-6mm', 1], ['botol-minum-sport-1-liter', 2]]],
            ['INV-DUMMY-006', $users['joko@toko.test'], 'cancelled', 'transfer', 15, [['laptop-probook-14-intel-core-i5', 1]]],
            ['INV-DUMMY-007', $users['putri@toko.test'], 'completed', 'transfer', 25, [['kopi-bubuk-arabika-200g', 3], ['madu-murni-hutan-500ml', 1]]],
            ['INV-DUMMY-008', $users['dedi@toko.test'], 'shipped', 'cod', 4, [['tenda-camping-4-orang', 1], ['lampu-meja-led-belajar', 1]]],
            ['INV-DUMMY-009', $users['maya@toko.test'], 'pending', 'transfer', 0, [['gitar-akustik-pemula', 1]]],
            ['INV-DUMMY-010', $budi, 'completed', 'midtrans', 30, [['headphone-nirkabel-soundmax-pro', 1], ['mouse-wireless-logitech-style', 1]]],
        ];

        foreach ($orders as [$code, $user, $status, $payment, $daysAgo, $lines]) {
            $total = 0;
            $itemRows = [];
            foreach ($lines as [$slug, $qty]) {
                $product = Product::where('slug', $slug)->first();
                if (! $product) { continue; }
                $subtotal = (int) $product->price * $qty;
                $total += $subtotal;
                $itemRows[] = ['product' => $product, 'quantity' => $qty, 'subtotal' => $subtotal];
            }
            if (empty($itemRows)) { continue; }

            $order = Order::firstOrCreate(['code' => $code], [
                'user_id' => $user->id,
                'recipient_name' => $user->name,
                'phone' => '0812345678'.str_pad((string) random_int(10, 99), 2, '0', STR_PAD_LEFT),
                'address' => 'Jl. Contoh No. '.random_int(1, 99).', Jakarta (alamat dummy)',
                'payment_method' => $payment,
                'status' => $status,
                'total' => $total,
                'payment_status' => $payment === 'midtrans' ? ($status === 'pending' ? 'pending' : 'settlement') : null,
                'paid_at' => in_array($status, ['paid', 'shipped', 'completed'], true) ? now()->subDays($daysAgo)->addHours(2) : null,
                'created_at' => now()->subDays($daysAgo),
                'updated_at' => now()->subDays($daysAgo),
            ]);

            if ($order->items()->count() === 0) {
                foreach ($itemRows as $row) {
                    $order->items()->create([
                        'product_id' => $row['product']->id,
                        'product_name' => $row['product']->name,
                        'price' => $row['product']->price,
                        'quantity' => $row['quantity'],
                        'subtotal' => $row['subtotal'],
                    ]);
                }
            }
        }
    }
}
