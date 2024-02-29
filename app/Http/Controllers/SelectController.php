<?php

namespace App\Http\Controllers;

use App\Models\MasterUpt;
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
        $data = MasterUpt::select('id', 'nama')->get();
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
        $data = MasterProvinsi::select('id', 'nama')->get();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function SelectKota(string $id): JsonResponse
    {
        $data = MasterKotaKab::where('provinsi_id', $id)->select('id', 'nama')->get();
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
