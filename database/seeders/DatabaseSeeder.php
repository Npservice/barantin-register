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
            MasterNegaraSeeder::class,
            MasterUptSeeder::class,
            MasterProvinsiSeeder::class,
            MasterKotaKabSeeder::class,
            AdminSeeder::class,
            PjBaratanKppSeeder::class,
            PjBaratinSeeder::class,
            PreRegisterSeeder::class
        ]);
    }
}
