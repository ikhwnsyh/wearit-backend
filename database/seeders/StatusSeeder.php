<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'status_name' => 'Menunggu pembayaran',
            ],
            [
                'status_name' => 'Menunggu approval',
            ],
            [
                'status_name' => 'Sedang diproses',
            ],
            [
                'status_name' => 'Sedang dikirim',
            ],
            [
                'status_name' => 'Selesai',
            ],
            [
                'status_name' => 'Ditolak',
            ],


        ];
        foreach ($data as $status) {
            $slug = Str::slug($status['status_name']);

            Status::create([
                'status_name' => $status['status_name'],
                'slug' => $slug,
            ]);
        }
    }
}
