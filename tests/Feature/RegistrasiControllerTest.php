<?php

namespace Tests\Feature;

use App\Models\Alamat;
use App\Models\Body;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrasiControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_invalidCredential()
    {
        $user = User::factory()->make();
        $register = $this->post('api/register', [
            'email' => $user->email,
            'name' => $user->name,
            'password' => 'password-berbeda', //password berbeda dengan confirm pasword
            'password_confirmation' => 'password',
            'gender' => 'pria',
            'handphone' => 4124124142,
            'alamat' => 'komplek bdn',
            'province_id' => 11,
            'regency_id' => 1101,
            'district_id' => 1101010,
            'tinggi_badan' => 140,
            'berat_badan' => 40,
            'lingkar_perut' => 90,
        ]);
        $register->assertStatus(422);
    }

    public function test_validRegistration() //registrasi berhasil
    {
        $user = User::factory()->make();
        $register = $this->post('api/register', [
            'email' => $user->email,
            'name' => $user->name,
            'password' => 'konsumen123',
            'password_confirmation' => 'konsumen123',
            'gender' => 'pria',
            'handphone' => 4124124142,
            'alamat' => 'komplek bdn',
            'province_id' => 11,
            'regency_id' => 1101,
            'district_id' => 1101010,
            'tinggi_badan' => 170,
            'berat_badan' => 60,
            'lingkar_perut' => 100,
        ]);
        $register->assertStatus(201)
            ->assertSee('Registrasi berhasil!');
    }

    public function test_regitrationObesitas() //registrasi gagal karena bmi obestias
    {
        $user = User::factory()->make();
        $register = $this->post('api/register', [
            'email' => $user->email,
            'name' => $user->name,
            'password' => 'konsumen123',
            'password_confirmation' => 'konsumen123',
            'gender' => 'pria',
            'handphone' => 4124124142,
            'alamat' => 'komplek bdn',
            'province_id' => 11,
            'regency_id' => 1101,
            'district_id' => 1101010,
            'tinggi_badan' => 160,
            'berat_badan' => 90,
            'lingkar_perut' => 90,
        ]);
        $register->assertStatus(200)
            ->assertSee('Maaf kategori BMI obesitas belum tersedia!');
    }
}
