<?php

namespace App\Http\Controllers\Api\User;

use App\Helpers\ApiResponse;
use App\Models\MitraPerusahaan;
use App\Helpers\PaginationHelper;
use App\Helpers\BarantinApiHelper;
use App\Http\Controllers\Controller;

class MitraController extends Controller
{
    /**
     * @OA\Get(
     *     path="/user/mitra",
     *     summary="Ambil Semua User Mitra di masing masing perngguna jasa",
     *     tags={"User"},
     *     security={{ "bearer_token": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=1
     *                         ),
     *                         @OA\Property(
     *                             property="nama_mitra",
     *                             type="string",
     *                             example="Nama Mitra"
     *                         ),
     *                         @OA\Property(
     *                             property="jenis_identitas_mitra",
     *                             type="string",
     *                             example="KTP"
     *                         ),
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getAllMitraUser()
    {
        $register = MitraPerusahaan::query();

        if (request()->user()->role != 'cabang' && request()->user()->role != 'perorangan') {
            $data = $register->where('pj_baratin_id', auth('sanctum')->user()->baratin->id)->orderBy('created_at', 'desc');
        } elseif (request()->user()->role == 'cabang') {
            $data = $register->where('barantin_cabang_id', auth('sanctum')->user()->baratincabang->id)->orderBy('created_at', 'desc');
        } else {
            return ApiResponse::errorResponse('Data not found', 404);
        }
        if ($data->count() > 0) {
            return ApiResponse::successResponse('Semua Mitra pengguna jasa', self::renderDataResponses($data->get()), false);
        }
        return ApiResponse::errorResponse('data not found', 404);
    }
    /**
     * @OA\Get(
     *     path="/user/mitra/{mitra_id}",
     *     summary="Ambil data mitra pengguna jasa berdasarkan id",
     *     tags={"User"},
     *     security={{ "bearer_token": {} }},
     *     parameters={
     *         {
     *             "in": "path",
     *             "name": "mitra_id",
     *             "required": true,
     *             "schema": {
     *                 "type": "string"
     *             }
     *         }
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="Successful",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="nama_mitra",
     *                         type="string",
     *                         example="Nama Mitra"
     *                     ),
     *                     @OA\Property(
     *                         property="jenis_identitas_mitra",
     *                         type="string",
     *                         example="KTP"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getMitraByID(string $mitra_id)
    {
        $data = MitraPerusahaan::find($mitra_id);
        if ($data) {
            return ApiResponse::successResponse('Detail mitra pengguna jasa', self::renderDataResponse($data), false);
        }
        return ApiResponse::errorResponse('data not found', 404);
    }
    private static function renderDataResponses($data, bool $pagination = false): array
    {
        $response = [];
        foreach ($data as $item) {
            $response[] = self::renderDataResponse($item);
        }
        if ($pagination) {
            $response = PaginationHelper::pagination($data, $response);
        }
        return $response;
    }

    private static function renderDataResponse($data): array
    {

        $negara = BarantinApiHelper::getMasterNegaraByID($data->master_negara_id);
        $provinsi = $data->master_provinsi_id ? BarantinApiHelper::getMasterProvinsiByID($data->master_provinsi_id) : null;
        $kota = $data->master_kota_kab_id ? BarantinApiHelper::getMasterKotaByIDProvinsiID($data->master_kota_kab_id, $data->master_provinsi_id) : null;

        $data = [
            "mitra_id" => $data->id,
            "nama_perusahaan_induk" => $data->baratin->nama_perusahaan ?? null,
            "nama_perusahaan_cabang" => $data->baratincabang->nama_perusahaan ?? null,
            "nama_mitra" => $data->nama_mitra,
            "jenis_identitas_mitra" => $data->jenis_identitas_mitra,
            "nomor_identitas_mitra" => $data->nomor_identitas_mitra,
            "alamat_mitra" => $data->alamat_mitra,
            "telepon_mitra" => $data->telepon_mitra,
            "negara" => $negara['nama'] ?? null,
            "provinsi" => $provinsi['nama'] ?? null,
            "kota" => $kota['nama'] ?? null,
        ];
        return $data;
    }
}
