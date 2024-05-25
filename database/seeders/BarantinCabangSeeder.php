<?php

namespace Database\Seeders;

use App\Models\Register;
use App\Models\PreRegister;
use Faker\Factory as Faker;
use App\Models\BarantinCabang;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BarantinCabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');



        foreach (range(1, 30) as $index) {
            // $provinsi_id = $faker->numberBetween(1, 34); // assuming 34 provinces in Indonesia
            DB::transaction(function () use ($faker) {
                $nama = $faker->name;
                $email = $faker->unique()->safeEmail;

                $induk = PreRegister::where('jenis_perusahaan', 'induk')->inRandomOrder()->first();
                $register = PreRegister::create([
                    'pemohon' => 'perusahaan',
                    'nama' => $nama,
                    'email' => $email,
                    'verify_email' => Carbon::now(),
                    'jenis_perusahaan' => 'cabang',
                    'pj_baratin_id' => $induk->baratin->id,
                ]);

                $baratin = BarantinCabang::create([
                    'kode_perusahaan' => $faker->unique()->randomNumber(5),
                    'pre_register_id' => $register->id,
                    'pj_baratin_id' => $induk->baratin->id,
                    'password' => bcrypt('password'), // assuming password is always 'password'
                    'nama_perusahaan' => $faker->company,
                    'jenis_identitas' => $faker->randomElement(['KTP', 'NPWP', 'PASSPORT']),
                    'nomor_identitas' => $faker->unique()->randomNumber(8),
                    'alamat' => $faker->address,
                    'telepon' => $faker->phoneNumber,
                    'nama_cp' => $faker->name,
                    'alamat_cp' => $faker->address,
                    'telepon_cp' => $faker->phoneNumber,
                    'kota' => 1101,
                    'provinsi_id' => 11,
                    'negara_id' => 99, // assuming flat 99 for country
                    'nama_tdd' => $faker->name,
                    'jenis_identitas_tdd' => $faker->randomElement(['KTP', 'NPWP', 'PASSPORT']),
                    'nomor_identitas_tdd' => $faker->unique()->randomNumber(8),
                    'jabatan_tdd' => $faker->jobTitle,
                    'alamat_tdd' => $faker->address,
                    'nama_pendaftar' => $faker->name,
                    'ktp_pendaftar' => $faker->unique()->randomNumber(8),
                    'jenis_perusahaan' => $faker->randomElement(['PEMILIK_BARANG', 'PPJK', 'EMKL', 'EMKU', 'LAINNYA']),
                    'kontak_ppjk' => $faker->phoneNumber,
                    'email' => $register->email,
                    'fax' => $faker->phoneNumber,
                    'kecamatan_id' => $faker->numberBetween(1, 100), // assuming 100 kecamatan
                    'status_import' => $faker->randomElement([
                        25,
                        26,
                        27,
                        28,
                        29,
                        30,
                        31,
                        32,
                    ]),
                    'lingkup_aktifitas' => '1,2,3,4',
                    'is_active' => $faker->boolean,
                    'status_prioritas' => $faker->boolean,
                ]);

                Register::create([
                    'barantin_cabang_id' => $baratin->id,
                    'master_upt_id' => 2100,
                    'status' => 'DISETUJUI',
                    'pre_register_id' => $register->id
                ]);
            });

        }
    }
}
