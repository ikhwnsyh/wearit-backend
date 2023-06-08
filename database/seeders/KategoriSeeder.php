<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kategori::create(
            [
                'kategori' => "UWPB (Underweight, Pendek, Buncit)",
                'asset' => '3d_images/UWTTB_BAJU_A_UKURAN_S.glb'
            ],

        );
        Kategori::create(
            [
                'kategori' => "UWPB (Underweight, Sedang, Buncit)",
                'asset' => '3d_images/UWTTB_BAJU_A_UKURAN_S.glb'
            ],


        );
        Kategori::create(
            [
                'kategori' => "UWPB (Underweight, Tinggi, Buncit)",
                'asset' => '3d_images/UWTTB_BAJU_A_UKURAN_S.glb'
            ],


        );
        Kategori::create(
            [
                'kategori' => "UWPB (Underweight, Pendek, Buncit)",
                'asset' => '3d_images/UWTTB_BAJU_A_UKURAN_S.glb'
            ],

        );
    }
}
