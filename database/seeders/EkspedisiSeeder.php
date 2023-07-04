<?php

namespace Database\Seeders;

use App\Models\Ekspedisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EkspedisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ekspedisi::create([
            'nama_ekspedisi' => "JNE",
            'ongkir' => 12000
        ]);

        Ekspedisi::create([
            'nama_ekspedisi' => "Anteraja",
            'ongkir' => 11000
        ]);
        Ekspedisi::create([
            'nama_ekspedisi' => "SiCepat",
            'ongkir' => 12000
        ]);
        Ekspedisi::create([
            'nama_ekspedisi' => "J&T",
            'ongkir' => 12000
        ]);
    }
}
