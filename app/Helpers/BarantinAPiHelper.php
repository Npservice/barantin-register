<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class BarantinApiHelper
{
    private static $baseUrl = 'https://api.karantinaindonesia.go.id';
    private static $dataMasterUpt = null;
    private static $dataMasterProvinsi = null;
    private static $dataMasterKota = [];
    public static function getBaseUrl(): string
    {
        return self::$baseUrl;
    }
    /**
     * Melakukan panggilan API ke endpoint yang ditentukan.
     * @param string $endpoint Endpoint yang akan dipanggil.
     * @return JsonResponse Respon dari API.
     */
    private static function makeApiCall(string $endpoint): JsonResponse
    {
        $response = Http::withoutVerifying()->get(self::$baseUrl . $endpoint);

        if ($response->successful()) {
            return response()->json($response->json()['data']);
        } else {
            return response()->json(['error' => 'Failed to fetch data', 'status' => $response->status()]);
        }
    }

    /**
     * Mengambil data master UPT dari API dan menyimpannya dalam cache jika belum ada.
     * @return JsonResponse Respon JSON yang mengandung data master UPT.
     */
    public static function GetDataMasterUpt(): JsonResponse
    {
        $cacheKey = 'dataMasterUpt';
        $cacheDuration = 60 * 24; // 1 hari dalam menit

        if (self::$dataMasterUpt === null || now()->diffInMinutes(cache()->get($cacheKey . '_timestamp', now()->subDay())) >= $cacheDuration) {
            self::$dataMasterUpt = self::makeApiCall('/barantin-sys/upt/induk');
            cache()->put($cacheKey, self::$dataMasterUpt, $cacheDuration);
            cache()->put($cacheKey . '_timestamp', now(), $cacheDuration);
        } else {
            self::$dataMasterUpt = cache()->get($cacheKey);
        }

        return self::$dataMasterUpt;
    }

    /**
     * Mengambil data master provinsi dari API dan menyimpannya dalam cache jika belum ada.
     * @return JsonResponse Respon JSON yang mengandung data master provinsi.
     */
    public static function GetDataMasterProvinsi(): JsonResponse
    {
        $cacheKey = 'dataMasterProvinsi';
        $cacheDuration = 60 * 24; // 1 hari dalam menit

        if (self::$dataMasterProvinsi === null || now()->diffInMinutes(cache()->get($cacheKey . '_timestamp', now()->subDay())) >= $cacheDuration) {
            self::$dataMasterProvinsi = self::makeApiCall('/barantin-sys/provinsi');
            cache()->put($cacheKey, self::$dataMasterProvinsi, $cacheDuration);
            cache()->put($cacheKey . '_timestamp', now(), $cacheDuration);
        } else {
            self::$dataMasterProvinsi = cache()->get($cacheKey);
        }

        return self::$dataMasterProvinsi;
    }
    /**
     * Memperoleh data kota berdasarkan ID provinsi dari API dan melakukan caching data tersebut.
     * @param int $provisi_id ID dari provinsi yang ingin diambil datanya.
     * @return JsonResponse Respon JSON yang berisi data kota berdasarkan provinsi.
     */
    public static function GetDataMasterKota(int $provisi_id): JsonResponse
    {
        $cacheKey = 'dataMasterKota_' . $provisi_id;
        $cacheDuration = 60 * 24; // 1 hari dalam menit

        if (cache()->has($cacheKey) && now()->diffInMinutes(cache()->get($cacheKey . '_timestamp', now()->subDay())) < $cacheDuration) {
            self::$dataMasterKota[$provisi_id] = cache()->get($cacheKey);
        } else {
            self::$dataMasterKota[$provisi_id] = self::makeApiCall('/barantin-sys/kota/prov/' . $provisi_id);
            cache()->put($cacheKey, self::$dataMasterKota[$provisi_id], $cacheDuration);
            cache()->put($cacheKey . '_timestamp', now(), $cacheDuration);
        }
        return self::$dataMasterKota[$provisi_id];
    }
    /**
     * Mengambil data master provinsi berdasarkan ID.
     *
     * Fungsi ini akan memanggil API untuk mengambil data master provinsi.
     * Setelah itu, fungsi akan mencari provinsi dengan ID yang diberikan dan mengembalikan hasilnya.
     *
     * @param int $id ID dari provinsi yang ingin diambil.
     * @return mixed Mengembalikan data provinsi jika ditemukan, atau null jika tidak ditemukan.
     */
    public static function GetMasterProvisiByID($id)
    {
        $provinsiInstance = self::GetDataMasterProvinsi();
        return collect($provinsiInstance->original)->where('id', $id)->first();
    }

    /**
     * Mengambil data master kota berdasarkan ID kota dan ID provinsi.
     *
     * Fungsi ini akan memanggil API untuk mengambil data master kota berdasarkan ID provinsi.
     * Setelah itu, fungsi akan mencari kota dengan ID yang diberikan dan mengembalikan hasilnya.
     *
     * @param int $id ID dari kota yang ingin diambil.
     * @param int $provinsi_id ID dari provinsi yang berkaitan dengan kota.
     * @return mixed Mengembalikan data kota jika ditemukan, atau null jika tidak ditemukan.
     */
    public static function GetMasterKotaByID($id, $provinsi_id)
    {
        $kotaInstance = self::GetDataMasterKota($provinsi_id);
        return collect($kotaInstance->original)->where('id', $id)->first();
    }

}

