<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_getDataDiri()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'schmitt.tillman@example.net',
            'password' => 'konsumen123'
        ]);

        $dataDiri = $this->get('/api/profile', [
            'token' => $auth['token'],
        ]);
        $dataDiri->assertStatus(200);
    }

    public function test_validUpdateDataDiri()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'schmitt.tillman@example.net',
            'password' => 'konsumen123'
        ]);
        $newData = User::factory()->make();
        $updateDataDiri = $this->put('/api/update-profile', [
            'name' => $newData->name,
            'email' => 'schmitt.tillman@example.net',
            'password' => 'konsumen123',
            'password_confirmation' => 'konsumen123',
        ]);
        $updateDataDiri->assertStatus(200);
    }

    public function test_invalidUpdateDataDiri()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'schmitt.tillman@example.net',
            'password' => 'konsumen123'
        ]);
        $updateDataDiri = $this->put('/api/update-profile', [
            'name' => 'mr pudidi',
            'email' => 'mrpudidi@example.net', //email ini sudah digunakan
            'password' => 'konsumen123',
            'password_confirmation' => 'konsumen123',
        ]);
        $updateDataDiri->assertStatus(422);
    }

    public function test_getDataTubuh()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'schmitt.tillman@example.net',
            'password' => 'konsumen123'
        ]);
        $dataDiri = $this->get('/api/profile/body');
        $dataDiri->assertStatus(200);
    }

    public function test_validUpdateDataTubuh()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'mabel.goldner@example.com',
            'password' => 'konsumen123'
        ]);
        $updateDataDiri = $this->put('/api/update-data-tubuh', [
            'tinggi_badan' => 170,
            'berat_badan' => 42,
            'lingkar_perut' => 91,
        ]);
        $updateDataDiri->assertStatus(200);
    }
}
