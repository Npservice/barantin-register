<?php

namespace App\Http\Controllers\Api;

use App\Models\PjBaratin;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Helpers\JsonFilterHelper;
use App\Helpers\PaginationHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\BarantinAPIHelper;
use App\Http\Controllers\Controller;

class BarantinController extends Controller
{
    /**
     * @OA\Get(
     *     path="/barantin/{take}",
     *     tags={"Barantin"},
     *     summary="Semua Data Barantin",
     *     description="Semua Data Barantin endpoint: '/barantin/5' angka 5 adalah jumlah data yang akan diambil bisa disesuaikan sesuai kebutuhan",
     *     operationId="getAllBarantinData",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="take",
     *         in="path",
     *         description="Jumlah data yang akan diambil",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             minimum=1,
     *             maximum=100,
     *             default=5
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="barantin all data"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nama", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john.doe@example.com"),
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
    public function GetAllDataBarantin(int $take): JsonResponse
    {
        $data = PjBaratin::paginate($take);
        if ($data->count() > 0) {
            return ApiResponse::SuccessResponse('barantin all data', self::RenderResponseDataBarantins($data, true), true);
        }
        return ApiResponse::ErrorResponse('data not found', 404);
    }

    /**
     * @OA\Get(
     *     path="/barantin/{id}/detail",
     *     tags={"Barantin"},
     *     summary="Dapatkan Data Barantin Berdasarkan ID",
     *     description="Mengambil data Barantin menggunakan ID spesifik",
     *     operationId="getDataBarantinById",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="id",
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
     *             @OA\Property(property="message", type="string", example="barantin data by id"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nama", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="no_hp", type="string", example="08123456789"),
     *                 @OA\Property(property="alamat", type="string", example="Jl. Sudirman No. 123"),
     *                 @OA\Property(property="created_at", type="string", example="2023-02-28 12:34:56"),
     *                 @OA\Property(property="updated_at", type="string", example="2023-02-28 12:34:56")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="data not found")
     *         )
     *     )
     * )
     */
    public function GetDataBarantinById(string $id): JsonResponse
    {
        $data = PjBaratin::find($id);

        if ($data) {
            return ApiResponse::SuccessResponse('barantin data by id', self::RenderResponseDataBarantin($data), false);
        }
        return ApiResponse::ErrorResponse('data not found', 404);
    }


    /**
     * Merender data Barantin menjadi format response yang sesuai.
     * Jika pagination diaktifkan, akan menerapkan pagination pada response.
     *
     * @param array $data Data Barantin yang akan dirender.
     * @param bool $pagination Menentukan apakah pagination harus diterapkan.
     * @return array Array yang berisi data Barantin yang sudah dirender.
     */
    private static function RenderResponseDataBarantins($data, bool $pagination = true)
    {
        $response = [];
        foreach ($data as $index => $item) {
            $response[$index] = self::RenderResponseDataBarantin($item);
        }

        if ($pagination) {
            $response = PaginationHelper::pagination($data, $response);
        }

        return $response;
    }
    /**
     * Merender data Barantin menjadi format response yang sesuai.
     * Mengambil data provinsi dan kota dari helper dan memformatnya ke dalam response.
     *
     * @param object $data Objek data Barantin yang akan dirender.
     * @return array Array yang berisi data Barantin yang sudah diformat.
     */
    private static function RenderResponseDataBarantin($data)
    {
        $provinsi = BarantinApiHelper::GetMasterProvisiByID($data->provinsi_id);
        $kota = BarantinApiHelper::GetMasterKotaByID($data->kota, $data->provinsi_id);

        $response = [
            'id' => $data->id,
            'kode_perusahaan' => $data->kode_perusahaan,
            'nama_perusahaan' => $data->nama_perusahaan,
            'nama_alias_perusahaan' => $data->nama_alias_perusahaan,
            'jenis_identitas' => $data->jenis_identitas,
            'nomor_identitas' => $data->nomor_identitas,
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

            'status_import' => $data->status_import,
            'status_prioritas' => $data->status_prioritas,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at
        ];
        return $response;
    }

}

