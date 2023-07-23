<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Transaksi::create([
            'user_id' => 3,
            'alamat_id' => 1,
            'bukti_pembayaran' => 'test.png',
            'ekspedisi_id' => 1,
            'status_id' => 1,
            'paid' => false,
            'final_price' => 10000,
        ]);

        Transaksi::create([
            'user_id' => 3,
            'alamat_id' => 1,
            'bukti_pembayaran' => 'test.png',
            'ekspedisi_id' => 1,
            'status_id' => 1,
            'paid' => false,
            'final_price' => 20000,
        ]);

        Transaksi::create([
            'user_id' => 3,
            'alamat_id' => 1,
            'bukti_pembayaran' => 'test.png',
            'ekspedisi_id' => 1,
            'status_id' => 2,
            'paid' => true,
            'final_price' => 20000,
        ]);
    }
}
