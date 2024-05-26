<?php

namespace App\Http\Controllers\Api;

use App\Models\Register;
use App\Models\PjBaratin;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\BarantinCabang;
use App\Helpers\JsonFilterHelper;
use App\Helpers\PaginationHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\BarantinAPIHelper;
use App\Helpers\StatusImportHelper;
use App\Http\Controllers\Controller;

class BarantinController extends Controller
{

    private $uptPusatId;
    public function __construct()
    {
        $this->uptPusatId = env('UPT_PUSAT_ID', 1000);
    }
    /**
     * @OA\Get(
     *     path="/barantin/perusahaan/induk/{take}",
     *     tags={"Barantin Admin"},
     *     summary="Dapatkan Data Barantin Perusahaan Induk",
     *     description="Mengambil data Barantin Perusahaan Induk menggunakan parameter take",
     *     operationId="getAllDataBarantinPerusahaanInduk",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="take",
     *         in="path",
     *         description="Jumlah data yang akan diambil",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
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
    public function getAllDataBarantinPerusahaanInduk(int $take)
    {

        $data = Register::with('preregister', 'baratin')
            ->select('registers.*')
            ->whereHas('preregister', fn ($query) => $query->where('jenis_perusahaan', 'induk'))
            ->where('status', 'DISETUJUI')
            ->where('blockir', 0);

        if (request()->user()->upt_id != $this->uptPusatId) {
            $data = $data->where('master_upt_id', request()->user()->upt_id);
        }

        if ($data->exists()) {
            return ApiResponse::successResponse('barantin data perusahaan induk', self::renderResponseDataBarantins($data->paginate($take), true, 'induk'), true);
        }
        return ApiResponse::errorResponse('Data not found', 404);
    }

    /**
     * @OA\Get(
     *     path="/barantin/perusahaan/cabang/{take}",
     *     tags={"Barantin Admin"},
     *     summary="Dapatkan Data Barantin Perusahaan Cabang",
     *     description="Mengambil data Barantin Perusahaan Cabang",
     *     operationId="getAllDataBarantinPerusahaanCabang",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="take",
     *         in="path",
     *         description="Jumlah data yang ingin diambil",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="barantin data perusahaan cabang"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nama", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                     @OA\Property(property="no_hp", type="string", example="08123456789"),
     *                     @OA\Property(property="alamat", type="string", example="Jl. Sudirman No. 123"),
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
     *             @OA\Property(property="message", type="string", example="data not found")
     *         )
     *     )
     * )
     */
    public function getAllDataBarantinPerusahaanCabang(int $take)
    {
        $data = Register::with('preregister', 'baratincabang', 'baratincabang.baratininduk:id,nama_perusahaan')
            ->select('registers.*')
            ->whereHas('preregister', fn ($query) => $query->where('jenis_perusahaan', 'cabang'))
            ->where('status', 'DISETUJUI')
            ->where('blockir', 0);
        if (request()->user()->upt_id != $this->uptPusatId) {
            $data = $data->where('master_upt_id', request()->user()->upt_id);
        }
        if ($data->exists()) {
            return ApiResponse::successResponse('barantin data perusahaan cabang', self::renderResponseDataBarantins($data->paginate($take), true, 'cabang'), true);
        }
        return ApiResponse::errorResponse('Data not found', 404);
    }
    /**
     * @OA\Get(
     *     path="/barantin/perorangan/{take}",
     *     tags={"Barantin Admin"},
     *     summary="Dapatkan Data Barantin Perorangan",
     *     description="Mengambil data Barantin dengan pemohon perorangan",
     *     operationId="getDataBarantinPerorangan",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="take",
     *         in="path",
     *         description="Jumlah data yang ingin diambil",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="barantin data perorangan"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nama", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                     @OA\Property(property="no_hp", type="string", example="08123456789"),
     *                     @OA\Property(property="alamat", type="string", example="Jl. Sudirman No. 123"),
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
     *             @OA\Property(property="message", type="string", example="data not found")
     *         )
     *     )
     * )
     */
    public function getAllDataBarantinPerorangan(int $take)
    {
        $data = Register::with('preregister', 'baratin')
            ->select('registers.*')
            ->whereHas('preregister', fn ($query) => $query->where('pemohon', 'perorangan'))
            ->where('status', 'DISETUJUI')
            ->where('blockir', 0);
        if (request()->user()->upt_id != $this->uptPusatId) {
            $data = $data->where('master_upt_id', request()->user()->upt_id);
        }
        if ($data->exists()) {
            return ApiResponse::successResponse('barantin data perorangan', self::renderResponseDataBarantins($data->paginate($take), true, 'perorangan'), true);
        }
        return ApiResponse::errorResponse('Data not found', 404);
    }

    /**
     * @OA\Get(
     *     path="/barantin/{register_id}/register",
     *     tags={"Barantin Admin"},
     *     summary="Get Barantin Data By Register ID",
     *     description="Get Barantin Data By Register ID",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         description="Register ID",
     *         in="path",
     *         name="register_id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
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
    public function getDataBarantinByRegisterID(string $register_id): JsonResponse
    {
        $data = Register::find($register_id);

        if ($data) {
            return ApiResponse::successResponse('barantin data by register id', self::renderResponseDataBarantin($data, $data->preregister->jenis_perusahaan), false);
        }
        return ApiResponse::errorResponse('Data not found', 404);
    }
    /**
     * @OA\Get(
     *     path="/barantin/perusahaan/induk/{barantin_id}/detil",
     *     tags={"Barantin Admin"},
     *     summary="Get Barantin Data Detail By Barantin ID",
     *     description="Get Barantin Data Detail By Barantin ID",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         description="Barantin ID",
     *         in="path",
     *         name="barantin_id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
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
    public function detilDataBarantinPerusahaanIndukBarantinID(string $barantin_id)
    {
        $data = PjBaratin::with(['preregister'])->whereHas('preregister', fn ($query) => $query->where('jenis_perusahaan', 'induk'))->find($barantin_id);
        if ($data) {
            return ApiResponse::successResponse('barantin detail data perusahaan induk', self::renderResponseDataBarantinDetil($data, 'induk'), false);
        }
        return ApiResponse::errorResponse('Data not found', 404);
    }
    /**
     * @OA\Get(
     *     path="/barantin/perusahaan/cabang/{barantin_id}/detil",
     *     tags={"Barantin Admin"},
     *     summary="Get Barantin Data Detail By Barantin ID for Cabang",
     *     description="Get Barantin Data Detail By Barantin ID for Cabang",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         description="Barantin ID",
     *         in="path",
     *         name="barantin_id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
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
    public function detilDataBarantinPerusahaanCabangByBarantinID(string $barantin_id)
    {
        $data = BarantinCabang::with(['preregister', 'baratininduk'])->find($barantin_id);
        if ($data) {
            return ApiResponse::successResponse('barantin detail data perusahaan cabang', self::renderResponseDataBarantinDetil($data, 'cabang'), false);
        }
        return ApiResponse::errorResponse('Data not found', 404);
    }
    /**
     * @OA\Get(
     *     path="/barantin/perorangan/{barantin_id}/detil",
     *     tags={"Barantin Admin"},
     *     summary="Get Barantin Data Detail By Barantin ID for Perorangan",
     *     description="Get Barantin Data Detail By Barantin ID for Perorangan",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         description="Barantin ID",
     *         in="path",
     *         name="barantin_id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
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
    public function detilDataBarantinPeroranganByBarantinID(string $barantin_id)
    {
        $data = PjBaratin::with(['preregister'])->whereHas('preregister', fn ($query) => $query->where('pemohon', 'perorangan'))->find($barantin_id);
        if ($data) {
            return ApiResponse::successResponse('barantin detail data perorangan', self::renderResponseDataBarantinDetil($data, 'perorangan'), false);
        }
        return ApiResponse::errorResponse('Data not found', 404);
    }

    /**
     * Merender data Barantin menjadi format response yang sesuai.
     * Jika pagination diaktifkan, akan menerapkan pagination pada response.
     *
     * @param array $data Data Barantin yang akan dirender.
     * @param bool $pagination Menentukan apakah pagination harus diterapkan.
     * @return array Array yang berisi data Barantin yang sudah dirender.
     */
    private static function renderResponseDataBarantins($data, bool $pagination, string $jenisPerusahaan)
    {
        $response = [];
        foreach ($data as $index => $item) {
            $response[$index] = self::renderResponseDataBarantin($item, $jenisPerusahaan);
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
    private static function renderResponseDataBarantin($data, $jenisPerusahaan)
    {
        $provinsi = BarantinApiHelper::getMasterProvinsiByID($data->baratin->provinsi_id ?? $data->baratincabang->provinsi_id);
        $kota = BarantinApiHelper::getMasterKotaByIDProvinsiID($data->baratin->kota ?? $data->baratincabang->kota, $data->baratin->provinsi_id ?? $data->baratincabang->provinsi_id);
        $upt = BarantinApiHelper::getMasterUptByID($data->master_upt_id);


        $dataArray = [
            'register_id' => $data->id ?? null,
            $data->baratin ? 'barantin_id' : 'barantin_cabang_id' => $data->baratin->id ?? $data->baratincabang->id ?? null,
            'upt' => $upt['nama_satpel'] . ' - ' . $upt['nama'] ?? null,
            'kode_perusahaan' => $data->baratin->kode_perusahaan ?? $data->baratincabang->kode_perusahaan ?? null,
            'pemohon' => $data->preregister->pemohon ?? null,
            'jenis_perusahaan' => $data->baratin->jenis_perusahaan ?? $data->baratincabang->jenis_perusahaan ?? null,
            'nama_perusahaan' => $data->baratin->nama_perusahaan ?? $data->baratincabang->nama_perusahaan ?? null,
            'nama_alias_perusahaan' => $data->baratin->nama_alias_perusahaan ?? $data->baratincabang->nama_alias_perusahaan ?? null,
            'jenis_identitas' => $data->baratin->jenis_identitas ?? $data->baratincabang->jenis_identitas ?? null,
            'nomor_identitas' => $data->baratin->nomor_identitas ?? $data->baratincabang->nomor_identitas ?? null,
            // 'NITKU' => $data->baratin->nitku,
            'alamat' => $data->baratin->alamat ?? $data->baratincabang->alamat ?? null,
            'provinsi' => $provinsi ? $provinsi['nama'] : null,
            'kota' => $kota ? $kota['nama'] : null,
            'telepon' => $data->baratin->telepon ?? $data->baratincabang->telepon ?? null,
            'email' => $data->baratin->email ?? $data->baratincabang->telepon ?? null,
            'fax' => $data->baratin->fax ?? $data->baratincabang->fax ?? null,

            'nama_cp' => $data->baratin->nama_cp ?? $data->baratincabang->nama_cp ?? null,
            'alamat_cp' => $data->baratin->alamat_cp ?? $data->baratincabang->alamat_cp ?? null,
            'telepon_cp' => $data->baratin->telepon_cp ?? $data->baratincabang->telepon_cp ?? null,

            'nama_tdd' => $data->baratin->nama_tdd ?? $data->baratincabang->nama_tdd ?? null,
            'jenis_identitas_tdd' => $data->baratin->jenis_identitas_tdd ?? $data->baratincabang->jenis_identitas_tdd ?? null,
            'nomor_identitas_tdd' => $data->baratin->nomor_identitas_tdd ?? $data->baratincabang->nomor_identitas_tdd ?? null,
            'jabatan_tdd' => $data->baratin->jabatan_tdd ?? $data->baratincabang->jabatan_tdd ?? null,
            'alamat_tdd' => $data->baratin->alamat_tdd ?? $data->baratincabang->alamat_tdd ?? null,

            'status_import' => StatusImportHelper::statusRender($data->baratin->status_import ?? $data->baratincabang->status_import),
            'lingkup_aktifitas' => StatusImportHelper::aktifitasRender($data->baratin->lingkup_aktifitas ?? $data->baratincabang->lingkup_aktifitas),
        ];
        switch ($jenisPerusahaan) {
            case 'induk':
                return  self::insertAfterKey($dataArray, 'nomor_identitas', 'NITKU', $data->baratin->nitku ?? '000000');
            case 'cabang':
                $newArray = self::insertAfterKey($dataArray, 'jenis_perusahaan', 'perusahaan_induk', $data->baratincabang->baratininduk->nama_perusahaan);
                $newArray = self::insertAfterKey($newArray, 'nomor_identitas', 'NITKU', $data->baratincabang->nitku);
                return $newArray;
            default;
                return $dataArray;
        }
    }
    private static function renderResponseDataBarantinDetil($data, $jenisPerusahaan)
    {
        $provinsi = BarantinApiHelper::getMasterProvinsiByID($data->provinsi_id);
        $kota = BarantinApiHelper::getMasterKotaByIDProvinsiID($data->kota, $data->provinsi_id);

        $dataArray = [
            $jenisPerusahaan == 'cabang' ? 'barantin_cabang_id' : 'barantin_id' => $data->id,
            'kode_perusahaan' => $data->kode_perusahaan,
            'pemohon' => $data->preregister->pemohon,
            'jenis_perusahaan' => $data->jenis_perusahaan,
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
                return self::insertAfterKey($dataArray, 'nomor_identitas', 'NITKU', $data->nitku ?? '000000');
            case 'cabang':
                $newArray = self::insertAfterKey($dataArray, 'jenis_perusahaan', 'perusahaan_induk', $data->baratininduk->nama_perusahaan);
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
