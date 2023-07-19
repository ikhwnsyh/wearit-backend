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
