<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use WithFaker;


    // public function test_getAllProduct()
    // {
    //     $product = Product::factory()->create();
    //     $this->withoutExceptionHandling();
    //     $auth = $this->post('/api/login', [
    //         'email' => 'konsumen@wearit.com',
    //         'password' => 'konsumen123'
    //     ]);
    //     $dataProduct = $this->get('/api/index', [
    //         'token' => $auth['token'],
    //     ]);
    //     $dataProduct->assertStatus(200);
    // }


    // public function test_detailProduct()
    // {

    //     $this->withoutExceptionHandling();
    //     $auth = $this->post('/api/login', [
    //         'email' => 'konsumen@wearit.com',
    //         'password' => 'konsumen123'
    //     ]);
    //     $product = Product::first();
    //     $detailProduct = $this->get('/api/product/' . $product->slug, [
    //         'token' => $auth['token'],
    //     ]);
    //     $detailProduct->assertStatus(200);
    // }
    // public function test_detailProductNotFound()
    // {
    //     $this->withoutExceptionHandling();
    //     $auth = $this->post('/api/login', [
    //         'email' => 'konsumen@wearit.com',
    //         'password' => 'konsumen123'
    //     ]);
    //     $product = Product::first();
    //     $detailProduct = $this->get('/api/product/' . 'not-found', [
    //         'token' => $auth['token'],
    //     ]);
    //     $detailProduct->assertStatus(200)->assertSee('maaf data tidak ditemukan!');
    // }

    // public function test_detailProductWithoutAuthentication()
    // {
    //     $this->withoutExceptionHandling();

    //     $product = Product::first();

    //     $detailProduct = $this->get('/api/product/' . $product->slug);
    //     $detailProduct->assertStatus(401); // 401 untuk Unauthorized
    // }
    // public function test_getAllProductIsNull()
    // {

    //     $this->withoutExceptionHandling();
    //     $auth = $this->post('/api/login', [
    //         'email' => 'konsumen@wearit.com',
    //         'password' => 'konsumen123'
    //     ]);
    //     Product::truncate();
    //     $dataProduct = $this->get('/api/index');
    //     $dataProduct->assertStatus(200)->assertSee('Belum ada produk!');
    // }

    public function test_getAllProductAtDashboard()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        $data = $this->get('/api/dashboard/product');
        $data->assertStatus(200);
    }

    public function test_getAllProductAtDashboardIsNull()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        Product::truncate();
        $data = $this->get('/api/dashboard/product');
        $data->assertStatus(200)->assertSee('data produk kosong!');
    }
    public function test_createProduct()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        $images = [];
        for ($i = 1; $i <= 2; $i++) {
            $images[] = UploadedFile::fake()->image("image{$i}.jpg");
        }
        $zipFilePath = storage_path('image/image.zip');

        // Simulasikan file upload dengan menggunakan UploadedFile::fake()
        $zipFile = UploadedFile::fake()->createWithContent('file.zip', file_get_contents($zipFilePath));

        $storeProduct = $this->post('/api/dashboard/tambah-product', [
            'token' => $auth['token'],
            'product_name' => fake()->word(),
            'description' => 'lorem ipsum dalang mana',
            'price' => 100000,
            'stock_s' => 10,
            'stock_m' => 3,
            'stock_l' => 2,
            'image' =>  $images,
            'asset' => $zipFile,
        ]);
        $storeProduct->assertStatus(201);
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
    public function test_updateProduct()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        $product = Product::first();
        $updateProduct = $this->put('/api/update-product/' . $product->id, [
            'product_name' => 'human greatness black',
            'slug' => $product->slug,
            'thumbnail' => $product->thumbnail,
            'description' => $product->description,
            'price' => 25000,
            'stock_s' => 10,
            'stock_m' => 10,
            'stock_l' => 10,
        ]);

        $updateProduct->assertStatus(200)->assertSee('Data produk berhasil diupdate!');
    }

    public function test_updateProductFailed()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        $product = Product::first();
        $updateProduct = $this->put('/api/update-product/' . $product->id, [
            'product_name' => 'human greatness black',
            'slug' => $product->slug,
            'thumbnail' => $product->thumbnail,
            'description' => $product->description,

        ]);
        $updateProduct->assertStatus(422);
    }
    public function test_deleteProduct()
    {
        $this->withoutExceptionHandling();
        $auth = $this->post('/api/login', [
            'email' => 'admin@wearit.com',
            'password' => 'admin123'
        ]);
        $product = Product::first();
        $deleteProduct = $this->delete('/api/hapus-product/' . $product->slug);
        $deleteProduct->assertStatus(200)->assertSee('produk berhasil dihapus!');
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
