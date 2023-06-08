<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            EkspedisiSeeder::class,
            KategoriSeeder::class,
            UserSeeder::class,
            StatusSeeder::class,
            IndoRegionSeeder::class,
        ]);
    }
}
