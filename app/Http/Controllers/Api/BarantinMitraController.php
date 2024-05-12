<?php

namespace App\Http\Controllers\Api;

use App\Models\PjBaratin;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\BarantinCabang;
use App\Models\MitraPerusahaan;
use App\Helpers\BarantinApiHelper;
use App\Helpers\PaginationHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class BarantinMitraController extends Controller
{
    /**
     * @OA\Get(
     *     path="/mitra/all/{take}",
     *     tags={"Mitra"},
     *     summary="Mengambil Semua Data Mitra",
     *     description="Endpoint ini digunakan untuk mengambil semua data mitra dengan jumlah data yang ditentukan.",
     *     operationId="getAllDataMitra",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="take",
     *         in="path",
     *         description="Jumlah data mitra yang akan diambil",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             minimum=1,
     *             maximum=100,
     *             default=10
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data mitra berhasil diambil"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nama", type="string", example="PT. Mitra Sejahtera"),
     *                     @OA\Property(property="email", type="string", example="info@mitrasejahtera.com"),
     *                     @OA\Property(property="no_hp", type="string", example="081234567890"),
     *                     @OA\Property(property="alamat", type="string", example="Jl. Kemerdekaan No. 123"),
     *                     @OA\Property(property="created_at", type="string", example="2023-02-28 12:34:56"),
     *                     @OA\Property(property="updated_at", type="string", example="2023-02-28 12:34:56")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Data mitra tidak ditemukan")
     *         )
     *     )
     * )
     */
    public function GetAllDataMitra(int $take): JsonResponse
    {
        $data = MitraPerusahaan::paginate($take);
        if ($data->count() > 0) {
            return ApiResponse::SuccessResponse('barantin mitra data', self::RenderDataResponses($data, true), true);
        }
        return ApiResponse::ErrorResponse('data not found', 404);
    }
    /**
     * @OA\Get(
     *     path="/mitra/barantin/{barantin_id}",
     *     tags={"Mitra"},
     *     summary="Semua Data Mitra Perusahaan Berdasarkan ID Barantin",
     *     description="Semua Data Mitra Perusahaan Berdasarkan ID Barantin endpoint: '/barantin/{barantin_id}/mitra' barantin_id  adalah ID Barantin",
     *     operationId="getAllDataMitraByBaratinID",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="barantin_id",
     *         in="path",
     *         description="ID Barantin",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="barantin mitra data"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nama", type="string", example="PT. Mitra Sejahtera"),
     *                     @OA\Property(property="email", type="string", example="mitra.sejahtera@example.com"),
     *                     @OA\Property(property="no_hp", type="string", example="08123456789"),
     *                     @OA\Property(property="alamat", type="string", example="Jl. Sudirman No. 123"),
     *                     @OA\Property(property="created_at", type="string", example="2023-02-28 12:34:56"),
     *                     @OA\Property(property="updated_at", type="string", example="2023-02-28 12:34:56"),
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function GetAllDataMitraByBaratinID(string $barantin_id): JsonResponse
    {
        $data = MitraPerusahaan::where('pj_baratin_id', $barantin_id)->get();
        if ($data->count() > 0) {
            return ApiResponse::SuccessResponse('barantin mitra data', self::RenderDataResponses($data), false);
        }
        return ApiResponse::ErrorResponse('data not found', 404);
    }

    /**
     * @OA\Get(
     *     path="/mitra/cabang/{barantin_cabang_id}",
     *     tags={"Mitra"},
     *     summary="Semua Data Mitra Cabang Berdasarkan ID Cabang Barantin",
     *     description="Endpoint untuk mendapatkan semua data mitra berdasarkan ID cabang Barantin",
     *     operationId="getAllDataMitraByBaratinCabangID",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="barantin_cabang_id",
     *         in="path",
     *         description="ID Cabang Barantin",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data mitra cabang ditemukan"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nama_mitra", type="string", example="PT. Cabang Mitra Sejahtera"),
     *                     @OA\Property(property="email", type="string", example="cabang.mitra@example.com"),
     *                     @OA\Property(property="no_hp", type="string", example="081234567890"),
     *                     @OA\Property(property="alamat", type="string", example="Jl. Kemerdekaan No. 45"),
     *                     @OA\Property(property="created_at", type="string", example="2023-03-01 12:00:00"),
     *                     @OA\Property(property="updated_at", type="string", example="2023-03-01 12:00:00"),
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function GetAllDataMitraByBaratinCabangID(string $barantin_cabang_id): JsonResponse
    {
        $data = MitraPerusahaan::where('barantin_cabang_id', $barantin_cabang_id)->get();
        if ($data->count() > 0) {
            return ApiResponse::SuccessResponse('barantin mitra data', self::RenderDataResponses($data), false);
        }
        return ApiResponse::ErrorResponse('data not found', 404);
    }

    /**
     * @OA\Get(
     *     path="/mitra/{mitra_id}",
     *     tags={"Mitra"},
     *     summary="Mendapatkan Data Mitra Berdasarkan ID",
     *     description="Endpoint untuk mendapatkan data mitra berdasarkan ID yang diberikan",
     *     operationId="getAllDataMitraByID",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="mitra_id",
     *         in="path",
     *         description="ID Mitra",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data mitra ditemukan"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nama_mitra", type="string", example="PT. Mitra Sejahtera"),
     *                 @OA\Property(property="email", type="string", example="mitra@example.com"),
     *                 @OA\Property(property="no_hp", type="string", example="081234567890"),
     *                 @OA\Property(property="alamat", type="string", example="Jl. Kemerdekaan No. 45"),
     *                 @OA\Property(property="created_at", type="string", example="2023-03-01 12:00:00"),
     *                 @OA\Property(property="updated_at", type="string", example="2023-03-01 12:00:00"),
     *             )
     *         )
     *     )
     * )
     */
    public function GetAllDataMitraByID(string $mitra_id): JsonResponse
    {
        $data = MitraPerusahaan::find($mitra_id);
        if ($data) {
            return ApiResponse::SuccessResponse('barantin mitra data', self::RenderDataResponse($data), false);
        }
        return ApiResponse::ErrorResponse('data not found', 404);
    }

    private static function RenderDataResponses($data, bool $pagination = false): array
    {
        $response = [];
        foreach ($data as $item) {
            $response[] = self::RenderDataResponse($item);
        }
        if ($pagination) {
            $response = PaginationHelper::pagination($data, $response);
        }
        return $response;
    }

    private static function RenderDataResponse($data): array
    {

        $provinsi = $data->master_provinsi_id ? BarantinApiHelper::GetMasterProvisiByID($data->master_provinsi_id) : null;
        $kota = $data->master_kota_kab_id ? BarantinApiHelper::GetMasterKotaByID($data->master_kota_kab_id, $data->master_provinsi_id) : null;

        $data = [
            "id" => $data->id,
            "pj_baratin" => $data->baratin->nama_perusahaan ?? null,
            "barantin_cabang" => $data->baratincabang->nama_perusahaan ?? null,
            "nama_mitra" => $data->nama_mitra,
            "jenis_identitas_mitra" => $data->jenis_identitas_mitra,
            "nomor_identitas_mitra" => $data->nomor_identitas_mitra,
            "alamat_mitra" => $data->alamat_mitra,
            "telepon_mitra" => $data->telepon_mitra,
            "negara" => $data->master_negara_id,
            "provinsi" => $provinsi,
            "kota" => $kota,
            "created_at" => $data->created_at,
            "updated_at" => $data->updated_at
        ];
        return $data;
    }

}
