<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
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
        $this->withoutExceptionHandling();
        $response = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'password-salah'
        ]);
        $response->assertStatus(401);
    }

    public function test_loginUserSuccess()
    {
        $response = $this->post('/api/login', [
            'email' => 'konsumen@wearit.com',
            'password' => 'konsumen123'
        ]);
        $response->assertStatus(200);
    }

    public function testWrongFormatEmail()
    {
        $response = $this->post('/api/login', [
            'email' => 'konsumen',
            'password' => 'konsumen123'
        ]);
        $response->assertStatus(422);
    }
}
