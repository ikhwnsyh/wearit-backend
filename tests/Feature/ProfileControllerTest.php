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
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $dataDiri = $this->get('/api/profile');
        $dataDiri->assertStatus(200);
    }

    public function test_validUpdateDataDiri()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $newData = User::factory()->make();
        $updateDataDiri = $this->put('/api/update-profile', [
            'name' => $newData->name,
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123',
            'password_confirmation' => 'konsumen123',
        ]);
        $updateDataDiri->assertStatus(200);
    }

    public function test_invaliCredentialdUpdateDataDiri()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $updateDataDiri = $this->put('/api/update-profile', [
            'name' => 'mr pudidi',
            'email' => 'admin@wearit.com', //email ini sudah digunakan
            'password' => 'konsumen123',
            'password_confirmation' => 'konsumen123',
        ]);
        $updateDataDiri->assertStatus(422);
    }

    public function test_getDataTubuh()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $dataDiri = $this->get('/api/profile/body');
        $dataDiri->assertStatus(200);
    }

    public function test_validUpdateDataTubuh()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $updateDataDiri = $this->put('/api/update-data-tubuh', [
            'tinggi_badan' => 170,
            'berat_badan' => 42,
            'lingkar_perut' => 91,
        ]);
        $updateDataDiri->assertStatus(200);
    }

    public function test_invalidCredentialUpdateDatatTubuh()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $updateDataDiri = $this->put('/api/update-data-tubuh', [
            'tinggi_badan' => 201, //melewati batas maksimal tinggi badan
            'berat_badan' => 42,
            'lingkar_perut' => 91,
        ]);
        $updateDataDiri->assertStatus(422);
    }
    public function test_invalidUpdateDataTubuhObesitas()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $updateDataDiri = $this->put('/api/update-data-tubuh', [
            'tinggi_badan' => 140,
            'berat_badan' => 90,
            'lingkar_perut' => 90,
        ]);
        $updateDataDiri->assertStatus(200);
    }
}
