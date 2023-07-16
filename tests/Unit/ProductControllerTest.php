<?php

namespace Tests\Feature\Http\Controllers\API\Testing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_GetAllProductSuccess()
    {
        $auth = $this->post('/api/login', [
            'email' => 'syahputraikhwan14@gmail.com',
            'password' => '12345678'
        ]);
        $response = $this->get('/api/index', [
            'token' => $auth['token'],
        ]);
        $response->assertStatus(200);
    }
}
