<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@wearit.com',
            'password' => bcrypt('admin123'),
            'gender' => 'pria',
            'handphone' => 12334241,
            'is_admin' => true,
            'is_kurir' => false

        ]);
        User::create([
            'name' => 'JNE',
            'email' => 'jne@wearit.com',
            'password' => bcrypt('jne12345'),
            'gender' => 'pria',
            'handphone' => 235234534,
            'is_admin' => false,
            'is_kurir' => true
        ]);
    }
}
