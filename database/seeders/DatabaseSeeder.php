<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            MasterNegaraSeeder::class,
            MasterUptSeeder::class,
            MasterProvinsiSeeder::class,
            MasterKotaKabSeeder::class,
            PjBaratanKppSeeder::class,
            // PjBaratinSeeder::class
        ]);
    }
}
