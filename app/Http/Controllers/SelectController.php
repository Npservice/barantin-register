<?php

namespace App\Http\Controllers;

use App\Models\Register;
use App\Models\MasterUpt;
use App\Models\PjBaratin;
use App\Models\MasterNegara;
use Illuminate\Http\Request;
use App\Models\MasterKotaKab;
use App\Models\MasterProvinsi;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class SelectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function SelectUpt(): JsonResponse
    {
        if (request()->input('q')) {

            $data = MasterUpt::select('id', 'nama', 'nama_satpel')
                ->where('nama', 'LIKE', '%' . request()->input('q') . '%')
                ->orWhere('nama_satpel', 'LIKE', '%' . request()->input('q') . '%')
                ->get();

        } elseif (request()->input('upt_id')) {

            $data = MasterUpt::find(request()->input('upt_id'));

        } elseif (request()->input('pre_register_id')) {

            $data = MasterUpt::select('nama', 'id', 'nama_satpel')->whereIn('id', function ($query) {
                $query->select('master_upt_id')
                    ->from('registers')
                    ->where('pre_register_id', request()->input('pre_register_id'));
            })->get();
        } else {
            $data = MasterUpt::select('id', 'nama', 'nama_satpel')->get();
        }
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function SelectNegara(): JsonResponse
    {
        if (request()->input('negara_id')) {
            $data = MasterNegara::find(request()->input('negara_id'));
        } else {
            $data = MasterNegara::select('id', 'nama', 'kode')->get();
        }
        return response()->json($data);
    }
    public function SelectProvinsi(): JsonResponse
    {
        if (request()->input('provinsi_id')) {
            $data = MasterProvinsi::find(request()->input('provinsi_id'));
        } else {
            $data = MasterProvinsi::select('id', 'nama')->get();

        }
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function SelectKota(string $id): JsonResponse
    {
        if (request()->input('kota_id')) {
            $data = MasterKotaKab::find(request()->input('kota_id'));
        } else {
            $data = MasterKotaKab::where('provinsi_id', $id)->select('id', 'nama')->get();
        }
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     */
    public function SelectPerusahaanInduk()
    {
        $pj_barantin_id = Register::where('status', 'DISETUJUI')->distinct('pj_barantin_id')->pluck('pj_barantin_id');


        $perushaan = PjBaratin::select('id', 'nama_perusahaan')->whereIn('id', $pj_barantin_id)->get();

        return response()->json($perushaan);
    }
}
