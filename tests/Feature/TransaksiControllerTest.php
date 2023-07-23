<?php

namespace Tests\Feature;

use App\Models\Alamat;
use App\Models\Detail;
use App\Models\Status;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
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

    public function test_getDataTransactionNull()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $auth = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $dataTransaksi = $this->get('api/profile/data-transaction');
        $dataTransaksi->assertStatus(200)->assertSee('Tidak ada transaksi yang berjalan!');
    }

    public function test_getDataTransaction()
    {
        $user = User::factory()->create();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);

        $auth = $this->get('api/profile/data-transaction');
        $auth->assertStatus(200);
    }

    public function test_getDataTransactionMenungguPembayaran()
    {
        $this->withoutExceptionHandling();

        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123',
        ]);

        $auth = $this->get('api/profile/menunggu-pembayaran');
        $auth->assertStatus(200);
    }

    public function test_getDataTransactionMenungguPembayaran_isNull()
    {
        $user = User::factory()->create();
        $auth = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $dataTransaksi = $this->get('api/profile/menunggu-pembayaran');
        $dataTransaksi->assertStatus(200)->assertSee('Tidak ada transaksi yang menunggu untuk dibayar!');
    }
    public function test_getDetailDataTransactionMenungguPembayaran_NotFound()
    {
        $user = User::factory()->create();
        $auth = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $dataTransaksi = $this->get('api/profile/menunggu-pembayaran/' . 'not-found');
        $dataTransaksi->assertStatus(404)->assertSee('data transaksi menunggu pembayaran tidak ditemukan');
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
        $this->withoutExceptionHandling();
        $response = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $alamat = Alamat::where('user_id', $response['user']['id'])->first();
        $product_ids = [1];
        $quantity = [2];
        $transaksi = $this->post('/api/bayar', [
            'user_id' => $response['user']['id'],
            'quantity' => $quantity,
            'product_id' => $product_ids,
            'final_price' => 20000,
            'ekspedisi_id' => 1,
            'alamat_id' => 1,
            'price' => 10000,
        ]);
        $transaksi->assertStatus(422);
    }

    public function test_invalidTransactionOverQuantity()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $alamat = Alamat::where('user_id', $response['user']['id'])->first();
        $size_id = [1];
        $product_ids = [1];
        $quantity = [6];
        $transaksi = $this->post('/api/bayar', [
            'user_id' => $response['user']['id'],
            'quantity' => $quantity,
            'size_id' => $size_id,
            'product_id' => $product_ids,
            'final_price' => 20000,
            'ekspedisi_id' => 1,
            'alamat_id' => 1,
            'price' => 10000,
        ]);
        $transaksi->assertStatus(200)->assertSee('kuantitas pada produk melebihi stok tersedia');
    }

    public function test_validTransaction()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $alamat = Alamat::where('user_id', $response['user']['id'])->first();
        $product_ids = [1];
        $size_id = [2, 3];
        $quantity = [1];
        $transaksi = $this->post('/api/bayar', [
            'user_id' => $response['user']['id'],
            'quantity' => $quantity,
            'size_id' => $size_id,
            'product_id' => $product_ids,
            'final_price' => 20000,
            'ekspedisi_id' => 1,
            'alamat_id' => 1,
            'price' => 10000,
        ]);
        $transaksi->assertStatus(200)->assertSee('transaksi berhasil dibuat!');
    }
    public function test_userFinishTransaction()
    {
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $user = User::factory()->create();
        $transaksi = Transaksi::where('user_id', $auth['user']['id'])->first();
        $auth = $this->put('api/selesai/' . $transaksi->id, ['end_transaction' => Carbon::now(), 'paid' => true]);
        $auth->assertStatus(200)->assertSee('Transaksi berhasil diselesaikan');
    }

    public function test_adminGetAllTransaction()
    {
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        $user = User::factory()->create();
        $transaksi = Transaksi::factory()->create(['user_id' => $user->id, 'alamat_id' => 1]);
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
            'user_id' => $user->id,
            'alamat_id' => 1
        ]);
        $response = $this->get('api/dashboard/transaksi/' . $slug);
        $response->assertStatus(200);
    }
    public function test_getDataTransactionWithFilterIsNull()
    {
        $this->withoutExceptionHandling();
        $slug = 'selesai';
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
        $transaksi = Transaksi::factory()->create(['user_id' => $user->id, 'status_id' => 2, 'alamat_id' => 1]);
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
        $transaksi = Transaksi::factory()->create(['user_id' => $user->id, 'status_id' => 2, 'alamat_id' => 1]);
        $response = $this->put('api/rejected/' . $transaksi->id);
        $response->assertStatus(200)->assertSee('Bukti tidak valid. Status pesanan menjadi dibatalkan!');
    }


    public function test_deliverTransaction()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        $user = User::factory()->create();
        $transaksi = Transaksi::factory()->create(['user_id' => $user->id, 'status_id' => 3, 'alamat_id' => 1]);
        $response = $this->put('api/sent/' . $transaksi->id);
        $response->assertStatus(200)->assertSee('Barang sedang dikirim menuju alamat!');
    }
}
