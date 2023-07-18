<?php

namespace Tests\Feature;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AlamatControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_getDataAlamat()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'schmitt.tillman@example.net',
            'password' => 'konsumen123'
        ]);

        $dataAlamat = $this->get('/api/profile/alamat', [
            'token' => $auth['token'],
        ]);
        $dataAlamat->assertStatus(200);
    }

    public function test_invalidDeleteDataAlamat()
    {
        // $this->withoutExceptionHandling();
        // $auth = $this->post('/api/login', [
        //     'email' => 'schmitt.tillman@example.net',
        //     'password' => 'konsumen123'
        // ]);
        // dd(Auth::id());
        // $dataAlamat = $this->delete('/api/profile/hapus-alamat/' . $alamat->id, [
        //     'token' => $auth['token'],
        // ]);
        // $dataAlamat->assertStatus(200);
    }
}
