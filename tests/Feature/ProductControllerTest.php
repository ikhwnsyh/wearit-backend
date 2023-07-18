<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use WithFaker;


    public function test_getAllProduct()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $dataProduct = $this->get('/api/index', [
            'token' => $auth['token'],
        ]);
        $dataProduct->assertStatus(200);
    }

    public function test_detailProduct()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $product = Product::first();
        $detailProduct = $this->get('/api/product/' . $product->slug, [
            'token' => $auth['token'],
        ]);
        $detailProduct->assertStatus(200);
    }

    public function test_createProduct()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        $storeProduct = $this->post('/api/dashboard/tambah-product', [
            'token' => $auth['token'],
            'product_name' => 'Baju polos biru',
            'description' => 'lorem ipsum dalang mana',
            'price' => 100000,
            'stock_s' => 10,
            'stock_m' => 3,
            'stock_l' => 2,
            'image' => 'https://source.unsplash.com/random',
            'asset' => 'https://source.unsplash.com/random',
        ]);
        dd($storeProduct);
    }

    public function test_createProductFailed()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        $storeProductFailed = $this->post('/api/dashboard/tambah-product', [
            'token' => $auth['token'],
        ]);
        $storeProductFailed->assertStatus(422);
    }


    public function test_getDashboardProdukSelainAdmin()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $dashboardProduct = $this->get('/api/dashboard/product', [
            'token' => $auth['token'],
        ]);
        $dashboardProduct->assertStatus(401);
    }
}
