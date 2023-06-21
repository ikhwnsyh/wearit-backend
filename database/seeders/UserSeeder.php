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
            'email' => 'admin@foodfest.com',
            'password' => bcrypt('admin123'),
            'gender' => 'pria',
            'handphone' => 12334241,
            'is_admin' => true,
        ]);
    }
}
