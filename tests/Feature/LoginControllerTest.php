<?php

namespace Tests\Feature;

use App\Models\User;
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
    public function test_loginInvalidCredential()
    {
        $response = $this->post('/api/login');
        $response->assertStatus(422);
    }
    public function test_authIsWrong()
    {
        $user = User::factory()->make();
        $this->withoutExceptionHandling();
        $response = $this->post('/api/login', [
            'email' =>  $user->email,
            'password' => 'password-salah'
        ]);
        $response->assertStatus(401)
            ->assertSee('Email atau Password Anda salah');
    }

    public function test_loginUserSuccess()
    {
        $user = User::factory()->create();
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200)
            ->assertSee('Login berhasil!');
    }
}
