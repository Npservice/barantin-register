<?php

namespace App\Http\Controllers\Api\User;

use App\Models\PjBaratin;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\BarantinCabang;
use App\Helpers\BarantinApiHelper;
use App\Helpers\StatusImportHelper;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/user/profile",
     *     tags={"User"},
     *     summary="Get User Profile",
     *     description="Get User Profile",
     *     security={{"bearer_token":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data Not Found"
     *     )
     * )
     */
    public function getProfileUser()
    {
        $data = PjBaratin::find(auth('sanctum')->user()->baratin->id) ??  BarantinCabang::find(auth('sanctum')->user()->baratincabang->id) ?? null;
        if ($data) {

            if ($data->preregister->pemohon == 'perorangan') {
                $jenisPerusahaan = 'perorangan';
            } else {
                $jenisPerusahaan = $data->preregister->jenis_perusahaan == 'cabang' ? 'cabang' : 'induk';
            }

            return ApiResponse::successResponse('Profile pengguna jasa berhasil ditemukan', self::renderResponseDataBarantinDetil($data, $jenisPerusahaan), false);
        }
        return ApiResponse::errorResponse('Data not found', 404);
    }
    private static function renderResponseDataBarantinDetil($data, $jenisPerusahaan)
    {
        $provinsi = BarantinApiHelper::getMasterProvinsiByID($data->provinsi_id);
        $kota = BarantinApiHelper::getMasterKotaByIDProvinsiID($data->kota, $data->provinsi_id);

        $dataArray = [
            $jenisPerusahaan == 'cabang' ? 'barantin_cabang_id' : 'barantin_id' => $data->id,
            'kode_perusahaan' => $data->kode_perusahaan,
            'pemohon' => $data->preregister->pemohon,
            'nama_perusahaan' => $data->nama_perusahaan,
            'nama_alias_perusahaan' => $data->nama_alias_perusahaan,
            'jenis_identitas' => $data->jenis_identitas,
            'nomor_identitas' => $data->nomor_identitas,
            // 'NITKU' => $data->nitku,
            'alamat' => $data->alamat,
            'provinsi' => $provinsi ? $provinsi['nama'] : null,
            'kota' => $kota ? $kota['nama'] : null,
            'telepon' => $data->telepon,
            'email' => $data->email,
            'fax' => $data->fax,

            'nama_cp' => $data->nama_cp,
            'alamat_cp' => $data->alamat_cp,
            'telepon_cp' => $data->telepon_cp,

            'nama_tdd' => $data->nama_tdd,
            'jenis_identitas_tdd' => $data->jenis_identitas_tdd,
            'nomor_identitas_tdd' => $data->nomor_identitas_tdd,
            'jabatan_tdd' => $data->jabatan_tdd,
            'alamat_tdd' => $data->alamat_tdd,

            'status_import' => StatusImportHelper::statusRender($data->status_import),
            'lingkup_aktifitas' => StatusImportHelper::aktifitasRender($data->lingkup_aktifitas),
        ];
        switch ($jenisPerusahaan) {
            case 'induk':
                $newArray = self::insertAfterKey($dataArray, 'pemohon', 'jenis_perusahaan', $data->jenis_perusahaan);
                $newArray = self::insertAfterKey($newArray, 'nomor_identitas', 'NITKU', $data->nitku ?? '000000');
                return $newArray;
            case 'cabang':
                $newArray = self::insertAfterKey($dataArray, 'pemohon', 'jenis_perusahaan', $data->jenis_perusahaan);
                $newArray = self::insertAfterKey($newArray, 'jenis_perusahaan', 'perusahaan_induk', $data->baratininduk->nama_perusahaan);
                $newArray = self::insertAfterKey($newArray, 'nomor_identitas', 'NITKU', $data->nitku);
                return $newArray;
            default;
                return $dataArray;
        }
    }
    private static function insertAfterKey($array, $key, $newKey, $newValue)
    {
        $newArray = [];
        $inserted = false;

        foreach ($array as $k => $v) {
            $newArray[$k] = $v;
            if ($k === $key) {
                $newArray[$newKey] = $newValue;
                $inserted = true;
            }
        }
        if (!$inserted) {
            $newArray[$newKey] = $newValue;
        }
        return $newArray;
    }
}
