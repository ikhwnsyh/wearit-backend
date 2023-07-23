<?php

namespace Database\Seeders;

use App\Models\Alamat;
use App\Models\Body;
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
        $konsumen = User::create([
            'name' => 'Konsumen',
            'email' => 'konsumen@wearit.com',
            'password' => bcrypt('konsumen123'),
            'gender' => 'pria',
            'handphone' => 235234534,
            'is_admin' => false,
            'is_kurir' => false
        ]);

        Alamat::create([
            'user_id' => $konsumen->id,
            'alamat' => 'komplek bdn 10231123',
            'province_id' => 11,
            'regency_id' => 1101,
            'district_id' => 1101010,
        ]);

        Body::create([
            'user_id' => $konsumen->id,
            'tinggi_badan' => 180,
            'berat_badan' => 60,
            'lingkar_perut' => 90,
            'kategori_id' => 11,
        ]);
    }
}
