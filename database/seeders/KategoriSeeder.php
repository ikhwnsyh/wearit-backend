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
                'id' => 1,
                'kategori' => "UWPTB ",
                'deskripsi' => 'Underweight pendek tidak buncit'
            ],
        );
        Kategori::create(
            [
                'id' => 2,
                'kategori' => "UWPB",
                'deskripsi' => 'Underweight pendek buncit'

            ],
        );
        Kategori::create(
            [
                'id' => 3,
                'kategori' => "UWSTB ",
                'deskripsi' => 'Underweight sedang tidak buncit'

            ],
        );
        Kategori::create(
            [
                'id' => 4,
                'kategori' => "UWSB ",
                'deskripsi' => 'Underweight sedang  buncit'
            ],
        );
        Kategori::create(
            [
                'id' => 5,
                'kategori' => "UWTTB ",
                'deskripsi' => 'Underweight tinggi tidak buncit'
            ],
        );
        Kategori::create(
            [
                'id' => 6,
                'kategori' => "UWTB ",
                'deskripsi' => 'Underweight tinggi buncit'
            ],
        );
        Kategori::create(
            [
                'id' => 7,
                'kategori' => "NPTB ",
                'deskripsi' => 'Normal pendek tidak buncit'
            ],
        );
        Kategori::create(
            [
                'id' => 8,
                'kategori' => "NPB ",
                'deskripsi' => 'Normal pendek buncit'
            ],
        );
        Kategori::create(
            [
                'id' => 9,
                'kategori' => "NSTB ",
                'deskripsi' => 'Normal sedang tidak buncit'
            ],
        );
        Kategori::create(
            [
                'id' => 10,
                'kategori' => "NSB ",
                'deskripsi' => 'Normal sedang buncit'
            ],
        );
        Kategori::create(
            [
                'id' => 11,
                'kategori' => "NTTB ",
                'deskripsi' => 'Normal tinggi tidak buncit'
            ],
        );
        Kategori::create(
            [
                'id' => 12,
                'kategori' => "NTB ",
                'deskripsi' => 'Normal tinggi buncit'
            ],
        );
        Kategori::create(
            [
                'id' => 13,
                'kategori' => "OWPTB ",
                'deskripsi' => 'Overweight pendek tidak buncit'
            ],
        );
        Kategori::create(
            [
                'id' => 14,
                'kategori' => "OWPB ",
                'deskripsi' => 'Overweight pendek buncit'
            ],
        );
        Kategori::create(
            [
                'id' => 15,
                'kategori' => "OWSTB ",
                'deskripsi' => 'Overweight sedang tidak buncit'
            ],
        );
        Kategori::create(
            [
                'id' => 16,
                'kategori' => "OWSB ",
                'deskripsi' => 'Overweight sedang buncit'
            ],
        );
        Kategori::create(
            [
                'id' => 17,
                'kategori' => "OWTTB ",
                'deskripsi' => 'Overweight tinggi tidak buncit'
            ],
        );
        Kategori::create(
            [
                'id' => 18,
                'kategori' => "OWTB ",
                'deskripsi' => 'Overweight tinggi buncit'
            ],
        );
    }
}
