<?php

namespace Tests\Feature;

use App\Models\Alamat;
use App\Models\Detail;
use App\Models\Status;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransaksiControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_getDataTransactioNull()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $auth = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $transaksi = Transaksi::factory()->create([
            'user_id' => $auth['user']['id'],
            'paid' => false,
        ]);
        $dataTransaksi = $this->get('api/profile/data-transaction');
        $dataTransaksi->assertStatus(200)->assertSee('Tidak ada transaksi yang berjalan!');
    }

    public function test_getDataTransaction()
    {
        $user = User::factory()->create();
        $auth = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $transaksi = Transaksi::factory()->create([
            'user_id' => $auth['user']['id'],
            'paid' => true,
        ]);
        $dataTransaksi = $this->get('api/profile/data-transaction');
        $dataTransaksi->assertStatus(200);
    }

    public function test_getDataTransactionMenungguPembayaran()
    {

        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $auth = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $transaksi = Transaksi::factory()->create([
            'user_id' => $auth['user']['id'],
            'paid' => false,
        ]);
        $dataTransaksi = $this->get('api/profile/menunggu-pembayaran');
        $dataTransaksi->assertStatus(200);
    }

    public function test_getDataTransactionMenungguPembayaranIsNull()
    {
        $user = User::factory()->create();
        $auth = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $dataTransaksi = $this->get('api/profile/menunggu-pembayaran');
        $dataTransaksi->assertStatus(200)->assertSee('Tidak ada transaksi yang menunggu untuk dibayar!');
    }

    public function test_halamanPembayaran()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);

        $transaksi = $this->get('api/beli-langsung');
        $transaksi->assertStatus(200);
    }

    public function test_invalidTransaction()
    {
        $response = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $alamat = Alamat::where('user_id', $response['user']['id'])->first();
        $transaksi = Transaksi::factory()->create([
            'user_id' => $response['user']['id'], //kondisi tidak memilih ukuran produk
            'paid' => false,
        ]);
        $transaksi = $this->post('/api/bayar');
        $transaksi->assertStatus(422);
    }


    public function test_adminGetAllTransaction()
    {
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        $user = User::factory()->create();
        $transaksi = Transaksi::factory()->create(['user_id' => $user->id]);
        $auth = $this->get('api/dashboard/transaksi');
        $auth->assertStatus(200);
    }

    public function test_adminGetNullTransaction()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        $transaksi = Transaksi::truncate();
        $auth = $this->get('api/dashboard/transaksi');
        $auth->assertStatus(200)->assertSee('Data transaksi kosong. tidak ada transaksi');
    }

    public function test_getDataTransactionWithFilter()
    {
        $this->withoutExceptionHandling();
        $slug = 'sedang-diproses';
        $status = Status::where('slug', $slug)->firstOrFail();
        $statusId = $status->id;
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        $user = User::factory()->create();
        $transaksi = Transaksi::factory()->create([
            'status_id' => $statusId,
            'user_id' => $user->id
        ]);
        $response = $this->get('api/dashboard/transaksi/' . $slug);
        $response->assertStatus(200);
    }
    public function test_getDataTransactionWithFilterIsNull()
    {
        $this->withoutExceptionHandling();
        $slug = 'paket-sedang-menunggu-pickup-oleh-jasa-kurir';
        $status = Status::where('slug', $slug)->firstOrFail();
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        $response = $this->get('api/dashboard/transaksi/' . $slug);
        $response->assertStatus(200)->assertSee('Tidak ada data transaksi dengan status');
    }

    public function test_approveTransaction()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        $user = User::factory()->create();
        $transaksi = Transaksi::factory()->create(['user_id' => $user->id, 'status_id' => 2]);
        $response = $this->put('api/approved/' . $transaksi->id);
        $response->assertStatus(200)->assertSee('Transaksi berhasil diapprove. Status transaksi menjadi sedang diproses');
    }

    public function test_rejectTransaction()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        $user = User::factory()->create();
        $transaksi = Transaksi::factory()->create(['user_id' => $user->id, 'status_id' => 2]);
        $response = $this->put('api/rejected/' . $transaksi->id);
        $response->assertStatus(200)->assertSee('Bukti tidak valid. Status pesanan menjadi dibatalkan!');
    }

    public function test_requestPickUp()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        $user = User::factory()->create();
        $transaksi = Transaksi::factory()->create(['user_id' => $user->id, 'status_id' => 3]);
        $response = $this->put('api/requestPickup/' . $transaksi->id);
        $response->assertStatus(200)->assertSee('Status updated! Paket sedang menunggu pickup dari pihak kurir');
    }
}
