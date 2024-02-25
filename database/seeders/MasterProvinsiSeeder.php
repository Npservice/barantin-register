<?php

namespace Database\Seeders;

use App\Models\MasterProvinsi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [11, '11', 'IDAC', 'ACEH', 'ACEH', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [12, '12', 'IDSU', 'SUMATERA UTARA', 'NORTH SUMATERA', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [13, '13', 'IDSB', 'SUMATERA BARAT', 'WEST SUMATERA', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [14, '14', 'IDRI', 'RIAU', 'RIAU', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [15, '15', 'IDJA', 'JAMBI', 'JAMBI', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [16, '16', 'IDSS', 'SUMATERA SELATAN', 'SOUTH SUMATERA', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [17, '17', 'IDBE', 'BENGKULU', 'BENGKULU', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [18, '18', 'IDLA', 'LAMPUNG', 'LAMPUNG', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [19, '19', 'IDBB', 'KEPULAUAN BANGKA BELITUNG', 'BANGKA BELITUNG', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [21, '21', 'IDKR', 'KEPULAUAN RIAU', 'RIAU ISLANDS', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [31, '31', 'IDJK', 'DKI JAKARTA', 'JAKARTA', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [32, '32', 'IDJB', 'JAWA BARAT', 'WEST JAVA', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [33, '33', 'IDJT', 'JAWA TENGAH', 'CENTRAL JAVA', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [34, '34', 'IDYO', 'DI YOGYAKARTA', 'YOGYAKARTA', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [35, '35', 'IDJI', 'JAWA TIMUR', 'EAST JAVA', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [36, '36', 'IDBT', 'BANTEN', 'BANTEN', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [51, '51', 'IDBA', 'BALI', 'BALI', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [52, '52', 'IDNB', 'NUSA TENGGARA BARAT', 'WEST NUSA TENGGARA', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [53, '53', 'IDNT', 'NUSA TENGGARA TIMUR', 'EAST NUSA TENGGARA', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [61, '61', 'IDKB', 'KALIMANTAN BARAT', 'WEST KALIMANTAN', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [62, '62', 'IDKT', 'KALIMANTAN TENGAH', 'CENTRAL KALIMANTAN', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [63, '63', 'IDKS', 'KALIMANTAN SELATAN', 'SOUTH KALIMANTAN', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [64, '64', 'IDKI', 'KALIMANTAN TIMUR', 'EAST KALIMANTAN', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [65, '65', 'IDKU', 'KALIMANTAN UTARA', 'NORTH KALIMANTAN', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [71, '71', 'IDSA', 'SULAWESI UTARA', 'NORTH SULAWESI', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [72, '72', 'IDST', 'SULAWESI TENGAH', 'CENTRAL SULAWESI', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [73, '73', 'IDSN', 'SULAWESI SELATAN', 'SOUTH SULAWESI', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [74, '74', 'IDSG', 'SULAWESI TENGGARA', 'SULAWESI TENGGARA', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [75, '75', 'IDGO', 'GORONTALO', 'GORONTALO', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [76, '76', 'IDSR', 'SULAWESI BARAT', 'WEST SULAWESI', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [81, '81', 'IDMA', 'MALUKU', 'MALUKU', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [82, '82', 'IDMU', 'MALUKU UTARA', 'NORTH MALUKU', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [91, '91', NULL, 'PAPUA', 'PAPUA', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [92, '92', NULL, 'PAPUA BARAT', 'WEST PAPUA', NULL, NULL],
            [93, '93', NULL, 'PAPUA SELATAN', 'SOUTH PAPUA', NULL, NULL],
            [94, '94', NULL, 'PAPUA TENGAH', 'CENTRAL PAPUA', '2017-08-10 11:57:33', '2017-08-10 11:57:33'],
            [95, '95', NULL, 'PAPUA PEGUNUNGAN', 'PAPUA MOUNTAINS', NULL, NULL],
            [96, '92', NULL, 'PAPUA BARAT DAYA', 'SOUTHWEST PAPUA', NULL, NULL],
        ];
        foreach ($data as $value) {

            MasterProvinsi::create([
                'id' => $value[0],
                'kode' => $value[1],
                'un_code' => $value[2],
                'nama' => $value[3],
                'nama_en' => $value[4],
            ]);
        }
    }
}
