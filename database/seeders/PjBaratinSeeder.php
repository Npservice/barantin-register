<?php

namespace Database\Seeders;

use App\Models\Register;
use App\Models\MasterUpt;
use App\Models\PjBaratin;
use App\Models\PreRegister;
use Faker\Factory as Faker;
use App\Models\MasterKotaKab;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PjBaratinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $pemohon = [
            'perusahaan',
            'perorangan'
        ];
        foreach (range(1, 20) as $index) {
            // $provinsi_id = $faker->numberBetween(1, 34); // assuming 34 provinces in Indonesia
            DB::transaction(function () use ($faker, $pemohon) {


                $kota = MasterKotaKab::with('provinsi:id')->inRandomOrder()->first();
                $jenis_pemohon = $faker->randomElement($pemohon);
                $nama = $faker->name;
                $email = $faker->unique()->safeEmail;
                $verify_email = $faker->boolean;

                $status = $faker->randomElement(['MENUNGGU', 'DISETUJUI', 'DITOLAK']);
                $register = PreRegister::create([
                    'pemohon' => $jenis_pemohon,
                    'nama' => $nama,
                    'email' => $email,
                    'verify_email' => Carbon::now(),
                    'status' => $status,
                ]);

                $baratin = PjBaratin::create([
                    'kode_perusahaan' => $faker->unique()->randomNumber(5),
                    'pre_register_id' => $register->id,
                    'password' => bcrypt('password'), // assuming password is always 'password'
                    'nama_perusahaan' => $faker->company,
                    'jenis_identitas' => $faker->randomElement(['KTP', 'NPWP', 'PASSPORT']),
                    'nomor_identitas' => $faker->unique()->randomNumber(8),
                    'alamat' => $faker->address,
                    'telepon' => $faker->phoneNumber,
                    'nama_cp' => $faker->name,
                    'alamat_cp' => $faker->address,
                    'telepon_cp' => $faker->phoneNumber,
                    'kota' => $kota->id,
                    'provinsi_id' => $kota->provinsi->id,
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
                    'status' => $register->status,
                    'is_active' => $faker->boolean,
                    'status_prioritas' => $faker->boolean,
                ]);

                Register::create(['pj_barantin_id' => $baratin->id, 'master_upt_id' => MasterUpt::inRandomOrder()->first()->id]);
            });

        }
    }
}
