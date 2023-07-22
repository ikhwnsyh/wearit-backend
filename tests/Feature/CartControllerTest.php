<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */


    public function test_getUserCart()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $cart = $this->get('/api/cart');
        $cart->assertStatus(200);
    }

    public function test_getUserCartQuantityExceed()  //kalo semisal di cart kuantitas ada 3, tp total stock cuma ada 2, nah itu automatis update
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $cart = $this->get('/api/cart');
        $cart->assertStatus(200);
    }


    public function test_userCartNull()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $auth = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $cartNull = $this->get('/api/cart');
        $cartNull->assertStatus(200)->assertSee('Keranjang kosong. Anda belum memasukkan barang ke keranjang!');
    }

    public function test_storeProductToCart()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $storeCart = $this->post('/api/cart', [
            'product_id' => 1,
            'quantity' => 1,
            'size_id' => 2,
        ]);
        $storeCart->assertStatus(201)->assertSee('succsess add to cart!');
    }
    public function test_quantityExceedStock()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $storeCart = $this->post('/api/cart', [
            'product_id' => 1,
            'quantity' => 1,
            'size_id' => 1,
        ]);

        $storeCart->assertStatus(200)
            ->assertSee('Jumlah barang pada keranjang anda sudah melebihi stock yang kami punya!');
    }

    public function test_sizeProductNotFound()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $storeCart = $this->post('/api/cart', [
            'product_id' => 1,
            'quantity' => 2,
            'size_id' => 7,
        ]);
        $storeCart->assertStatus(404)->assertSee('Size tidak ditemukan!');
    }

    public function test_productNotFound()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $storeCart = $this->post('/api/cart', [
            'product_id' => 200,
            'quantity' => 2,
            'size_id' => 4,
        ]);
        $storeCart->assertStatus(404)
            ->assertSee('Product tidak ditemukan!');
    }


    public function test_stockProductNull()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $storeCart = $this->post('/api/cart', [
            'product_id' => 1,
            'quantity' => 2,
            'size_id' => 3,
        ]);
        $storeCart->assertStatus(200)->assertSee('Stock barang 0. Gagal menambahkan barang ke cart');
    }
    public function test_deleteUserCart()
    {
        $user = User::factory()->create();
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $delete = $this->delete('/api/hapus-cart');
        $delete->assertStatus(200)->assertSee('data cart anda berhasil dihapus!');
    }
}
