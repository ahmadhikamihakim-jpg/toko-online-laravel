<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminUsersAndPhotoTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_melihat_daftar_pengguna_terdaftar(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        User::factory()->create(['name' => 'Pelanggan Satu', 'email' => 'satu@toko.test']);

        $this->actingAs($admin)->get('/admin/pengguna')
            ->assertOk()
            ->assertSee('Pengguna Terdaftar')
            ->assertSee('Pelanggan Satu')
            ->assertSee('satu@toko.test');
    }

    public function test_pelanggan_biasa_ditolak_dari_daftar_pengguna(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user)->get('/admin/pengguna')->assertForbidden();
    }

    public function test_admin_bisa_mengunggah_foto_produk(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::create(['name' => 'Uji', 'slug' => 'uji']);

        $this->actingAs($admin)->post('/admin/produk', [
            'category_id' => $category->id,
            'name' => 'Produk Foto Uji',
            'price' => 10000,
            'stock' => 3,
            'is_active' => '1',
            'photo' => UploadedFile::fake()->image('foto-uji.jpg', 400, 300),
        ])->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', ['name' => 'Produk Foto Uji']);
        $product = \App\Models\Product::where('name', 'Produk Foto Uji')->first();
        $this->assertNotEmpty($product->image);
        $this->assertFileExists(public_path($product->image));
        @unlink(public_path($product->image));
    }

    public function test_menu_pesanan_saya_disembunyikan_untuk_admin_tetapi_tampil_untuk_pelanggan(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $pelanggan = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin)->get('/')->assertOk()->assertDontSee('Pesanan Saya');
        auth()->logout();
        $this->actingAs($pelanggan)->get('/')->assertOk()->assertSee('Pesanan Saya');
    }

    public function test_admin_dapat_melihat_dan_memperbarui_profil(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get('/admin/profil')->assertOk()->assertSee('Pengaturan Profil');
        $this->actingAs($admin)->put('/admin/profil', [
            'name' => 'Admin Baru', 'email' => $admin->email, 'bio' => 'Pengelola toko online.',
        ])->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'name' => 'Admin Baru', 'bio' => 'Pengelola toko online.']);
    }

    public function test_admin_dapat_mengganti_kata_sandi_dengan_kata_sandi_lama_yang_benar(): void
    {
        $admin = User::factory()->create(['is_admin' => true]); // kata sandi bawaan pabrik: "password"
        $this->actingAs($admin)->put('/admin/profil/kata-sandi', [
            'current_password' => 'bukan-password', 'password' => 'rahasia-baru-123', 'password_confirmation' => 'rahasia-baru-123',
        ])->assertSessionHasErrors('current_password');
        $this->actingAs($admin)->put('/admin/profil/kata-sandi', [
            'current_password' => 'password', 'password' => 'rahasia-baru-123', 'password_confirmation' => 'rahasia-baru-123',
        ])->assertRedirect()->assertSessionHasNoErrors();
    }

    public function test_halaman_admin_memakai_sidebar_dan_non_admin_ditolak(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('admin-sidebar');
        $pelanggan = User::factory()->create(['is_admin' => false]);
        $this->actingAs($pelanggan)->get('/admin/profil')->assertForbidden();
    }

    public function test_dashboard_memakai_kartu_statistik_berwarna_dengan_pendapatan_di_bawah(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $html = $this->actingAs($admin)->get('/admin')->assertOk()->getContent();
        $this->assertStringContainsString('stat-grid', $html);
        $this->assertStringContainsString('stat-card biru', $html);
        $this->assertStringContainsString('stat-card pendapatan', $html);
        // kartu pendapatan muncul setelah kartu-kartu kecil
        $this->assertGreaterThan(strpos($html, 'stat-grid'), strpos($html, 'stat-card pendapatan'));
    }
}
