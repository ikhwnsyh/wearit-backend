<?php

namespace Tests\Feature\Http\Controllers\API\Testing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_loginEmailAndPasswordRequired()
    {
        $response = $this->post('/api/login');
        $response->assertStatus(422);
    }
    public function test_authIsWrong()
    {
        $response = $this->post('/api/login', [
            'email' => 'syahputraikhwan14@gmail.com',
            'password' => '123456789'
        ]);
        $response->assertStatus(401);
    }
    public function test_loginUserSuccess()
    {
        $response = $this->post('/api/login', [
            'email' => 'syahputraikhwan14@gmail.com',
            'password' => '12345678'
        ]);
        // $auth['token']
        $response->assertStatus(200);
    }
}
