<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SemuaHalamanBisaDiaksesTest extends TestCase
{
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
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
    }
   
}
