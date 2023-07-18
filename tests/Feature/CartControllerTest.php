<?php

namespace Tests\Feature;

use App\Models\Cart;
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
        $cart = $this->get('/api/cart', [
            'token' => $auth['token'],
            'id' => Auth::id(),
        ]);
        $cart->assertStatus(200);
    }

    public function test_userCartNull()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'zulauf.jayce@example.net',
            'password' => 'konsumen123'
        ]);

        $cartNull = $this->get('/api/cart', [
            'token' => $auth['token'],
            'id' => Auth::id(),
        ]);
        $cartNull->assertStatus(200);
    }

    public function test_storeProductToCart()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $storeCart = $this->post('/api/cart', [
            'token' => $auth['token'],
            'user_id' => Auth::id(),
            'product_id' => 1,
            'quantity' => 2,
            'size_id' => 1,
        ]);
        $storeCart->assertStatus(201);
    }
    public function test_sizeProductNotFound()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $storeCart = $this->post('/api/cart', [
            'token' => $auth['token'],
            'user_id' => Auth::id(),
            'product_id' => 1,
            'quantity' => 2,
            'size_id' => 4,
        ]);
        $storeCart->assertStatus(404);
    }

    public function test_productNotFound()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $storeCart = $this->post('/api/cart', [
            'token' => $auth['token'],
            'user_id' => Auth::id(),
            'product_id' => 20,
            'quantity' => 2,
            'size_id' => 4,
        ]);
        $storeCart->assertStatus(404);
    }

    public function test_quantityExceedStock()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $storeCart = $this->post('/api/cart', [
            'token' => $auth['token'],
            'user_id' => Auth::id(),
            'product_id' => 1,
            'quantity' => 2,
            'size_id' => 2,
        ]);
        $storeCart->assertStatus(200);
    }

    public function test_stockProductNull()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $storeCart = $this->post('/api/cart', [
            'token' => $auth['token'],
            'user_id' => Auth::id(),
            'product_id' => 1,
            'quantity' => 2,
            'size_id' => 3,
        ]);
        $storeCart->assertStatus(200);
    }
    public function test_deleteUserCart()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'syahputraikhwan14@gmail.com',
            'password' => '12345678'
        ]);

        $delete = $this->delete('/api/hapus-cart', [
            'token' => $auth['token'],
        ]);
        $delete->assertStatus(200);
    }
}
