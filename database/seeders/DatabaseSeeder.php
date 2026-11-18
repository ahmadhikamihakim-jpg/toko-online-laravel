<?php
namespace Database\Seeders;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder {
    public function run(): void {
        User::updateOrCreate(['email'=>'admin@toko.test'], ['name'=>'Admin','password'=>'password','is_admin'=>true]);
        User::updateOrCreate(['email'=>'budi@toko.test'], ['name'=>'Budi Santoso','password'=>'password','is_admin'=>false]);
        $data = [
            'Elektronik' => ['deskripsi'=>'Gadget dan perangkat elektronik pilihan.', 'produk'=>[
                ['Smartphone AndroMax X5 8/256GB', 2499000, 15, 'Smartphone layar 6,7 inci, kamera 50MP, baterai 5.000 mAh, dan penyimpanan lega 256GB.'],
                ['Laptop ProBook 14 Intel Core i5', 8750000, 8, 'Laptop ringan 14 inci dengan prosesor Intel Core i5, RAM 16GB, dan SSD 512GB untuk kerja dan kuliah.'],
                ['Headphone Nirkabel SoundMax Pro', 349000, 30, 'Headphone bluetooth dengan peredam bising aktif, bass mantap, dan baterai hingga 30 jam.'],
            ]],
            'Pakaian' => ['deskripsi'=>'Pakaian harian dan formal untuk pria dan wanita.', 'produk'=>[
                ['Kemeja Batik Pria Megamendung', 189000, 40, 'Kemeja batik lengan panjang motif Megamendung, bahan katun adem, cocok untuk kantor dan acara formal.'],
                ['Kaos Polos Katun Premium', 79000, 100, 'Kaos polos bahan katun combed 24s, nyaman dipakai seharian, tersedia berbagai warna dan ukuran.'],
                ['Celana Jeans Slim Fit Biru', 219000, 35, 'Celana jeans slim fit bahan denim stretch, tidak mudah luntur dan nyaman untuk bergerak.'],
            ]],
            'Sepatu & Tas' => ['deskripsi'=>'Sepatu dan tas untuk aktivitas sehari-hari.', 'produk'=>[
                ['Sepatu Lari AirFlex Running', 459000, 25, 'Sepatu lari ringan dengan sol empuk dan upper breathable, cocok untuk jogging dan olahraga.'],
                ['Tas Ransel Urban Anti Air', 259000, 20, 'Ransel 20 liter bahan anti air dengan slot laptop 15 inci dan banyak kantong organizer.'],
                ['Sepatu Sneakers Putih Kasual', 329000, 28, 'Sneakers putih model kasual, mudah dipadukan, sol karet anti slip dan nyaman dipakai jalan jauh.'],
            ]],
            'Rumah Tangga' => ['deskripsi'=>'Peralatan rumah tangga praktis dan awet.', 'produk'=>[
                ['Rice Cooker Serbaguna 1,8 Liter', 389000, 18, 'Penanak nasi 1,8 liter dengan fungsi menghangatkan dan mengukus, lapisan anti lengket mudah dibersihkan.'],
                ['Blender Kaca 2 in 1 Philips Style', 299000, 22, 'Blender tabung kaca 1,5 liter dengan penggiling bumbu, pisau stainless tajam untuk jus dan smoothie.'],
                ['Set Panci Stainless 12 Pcs', 549000, 12, 'Set panci stainless steel 12 pcs lengkap dengan tutup kaca, bisa dipakai di kompor gas dan induksi.'],
            ]],
        ];
        foreach ($data as $catName => $cat) {
            $category = Category::updateOrCreate(['slug'=>Str::slug($catName)], ['name'=>$catName,'description'=>$cat['deskripsi']]);
            foreach ($cat['produk'] as [$name,$price,$stock,$desc]) {
                Product::updateOrCreate(['slug'=>Str::slug($name)], [
                    'category_id'=>$category->id,'name'=>$name,'description'=>$desc,'price'=>$price,'stock'=>$stock,
                    'image'=>'images/products/'.Str::slug($name).'.jpg','is_active'=>true,
                ]);
            }
        }
    }
}
