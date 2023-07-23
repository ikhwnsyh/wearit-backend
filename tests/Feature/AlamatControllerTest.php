<?php

namespace Tests\Feature;

use App\Models\Alamat;
use App\Models\User;
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
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);

        $dataAlamat = $this->get('/api/profile/alamat');
        $dataAlamat->assertStatus(200);
    }
    public function test_dataAlamatIsNull()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $auth = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $dataAlamat = $this->get('/api/profile/alamat');
        $dataAlamat->assertStatus(200)
            ->assertSee('Alamat anda kosong!');
    }

    public function test_getDetailDataAlamat()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $alamat = Alamat::where('user_id', $auth['user']['id'])->first();
        $dataAlamat = $this->get('/api/profile/edit-alamat/' . $alamat->id);
        $dataAlamat->assertStatus(200);
    }
    public function test_getDetailDataAlamatNotFound()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $dataAlamat = $this->get('/api/profile/edit-alamat/' . 100);
        $dataAlamat->assertStatus(200)->assertSee('data alamat tidak ditemukan!');
    }
    public function test_createAlamat()
    {
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $alamat = Alamat::factory()->create();
        $create = $this->post('/api/profile/tambah-alamat', [
            'user_id' => $alamat->user_id,
            'alamat' => substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz-:,"), 0, 8),
            'province_id' => $alamat->province_id,
            'district_id' => $alamat->district_id,
            'regency_id' => $alamat->regency_id,
        ]);
        $create->assertStatus(201)->assertSee('Alamat berhasil ditambahakan!');
    }

    public function test_invalidCreateAlamat()
    {
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $alamat = Alamat::factory()->create();
        $create = $this->post('/api/profile/tambah-alamat', [
            'user_id' => $alamat->user_id,
            'alamat' => $alamat->alamat,
            'province_id' => $alamat->province_id,
            'district_id' => $alamat->district_id,
            'regency_id' => $alamat->regency_id,
        ]);
        $create->assertStatus(422);
    }

    public function test_updateAlamat()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $alamat = Alamat::where('user_id', $auth['user']['id'])->first();
        $updateAlamat = $this->put('/api/update-alamat/' . $alamat->id, ['alamat' => $alamat->alamat]);
        $updateAlamat->assertStatus(200)->assertSee('data alamat berhasil diupdate!');
    }
    public function test_invalidDeleteDataAlamat()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $auth = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $createAlamat = Alamat::factory()->create([
            'user_id' => $auth['user']['id'],
        ]);
        $alamat = Alamat::where('user_id', $auth['user']['id'])->first();
        $dataAlamat = $this->delete('/api/profile/hapus-alamat/' . $alamat->id);
        $dataAlamat->assertStatus(200)
            ->assertSee('Alamat gagal dihapus!');
    }

    public function test_validDeleteDataAlamat()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $createAlamat = Alamat::factory()->create([
            'user_id' => $auth['user']['id'],
        ]);
        $alamat = Alamat::where('user_id', $auth['user']['id'])->first();
        $dataAlamat = $this->delete('/api/profile/hapus-alamat/' . $alamat->id);
        $dataAlamat->assertStatus(200)
            ->assertSee('data alamat berhasil dihapus!');
    }
}
