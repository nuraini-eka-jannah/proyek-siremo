<?php

namespace Tests\Feature;

use App\Models\User; // <-- Pastikan mengimpor Model User di bagian atas
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SemuaHalamanBisaDiaksesTest extends TestCase
{
    // Jika test kamu berinteraksi dengan database (membuat user), aktifkan trait ini:
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_home_bisa_diakses(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_dashboard(): void
    {
        // 1. Buat 1 data user tiruan di database testing
        $user = User::factory()->create();

        // 2. Bertindak sebagai (actingAs) user tersebut, lalu akses URL yang benar
        $response = $this->actingAs($user)->get('/admin/dashboard');

        // 3. Pastikan sekarang statusnya sukses (200)
        $response->assertStatus(200);
    }
}