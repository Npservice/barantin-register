<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PreRegister;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $perorangan = PreRegister::where('pemohon', 'perorangan')->where('email', 'nandaputraservice@outlook.co.id')->first();
        $induk = PreRegister::where('jenis_perusahaan', 'induk')->where('email', 'ridlo.pakis@outlook.co.id')->first();
        $cabang = PreRegister::where('jenis_perusahaan', 'cabang')->where('email', 'nandaputraservice@gmail.com')->first();

        $userPerorangan = User::create([
            'nama' => $perorangan->baratin->nama_perusahaan,
            'email' => $perorangan->baratin->email,
            'username' => 'perorangan',
            'role' => 'perorangan',
            'status_user' => 1,
            'password' => 12345678
        ]);
        $perorangan->baratin()->update(['user_id' => $userPerorangan->id]);

        $userInduk = User::create([
            'nama' => $induk->baratin->nama_perusahaan,
            'email' => $induk->baratin->email,
            'username' => 'induk',
            'role' => 'induk',
            'status_user' => 1,
            'password' => 12345678
        ]);
        $induk->baratin()->update(['user_id' => $userInduk->id]);

        $userCabang = User::create([
            'nama' => $cabang->baratincabang->nama_perusahaan,
            'email' => $cabang->baratincabang->email,
            'username' => 'cabang',
            'role' => 'cabang',
            'status_user' => 1,
            'password' => 12345678
        ]);
        $cabang->baratincabang()->update(['user_id' => $userCabang->id]);
    }
}
