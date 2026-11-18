<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_daftar_bisa_dibuka(): void
    {
        $this->get('/daftar')->assertOk();
    }

    public function test_pengguna_baru_bisa_mendaftar_dan_langsung_masuk(): void
    {
        $response = $this->post('/daftar', [
            'name' => 'Pengguna Uji',
            'email' => 'uji@toko.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'uji@toko.test',
            'name' => 'Pengguna Uji',
            'is_admin' => false,
        ]);
    }

    public function test_email_yang_sama_tidak_bisa_daftar_dua_kali(): void
    {
        $this->post('/daftar', [
            'name' => 'Pengguna Uji',
            'email' => 'uji@toko.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);
        auth()->logout();

        $this->post('/daftar', [
            'name' => 'Peniru',
            'email' => 'uji@toko.test',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertSessionHasErrors('email');
    }
}
