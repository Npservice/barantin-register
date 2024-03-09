<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'nama' => 'admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => '12345678'
        ]);
        Admin::create([
            'nama' => 'kantorpusat',
            'email' => 'kantorpusat@gmail.com',
            'username' => 'kantorpusat',
            'password' => '12345678',
            'upt_id' => 1
        ]);
    }
}
