<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\BarantinCabang;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class UserCabangController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('ajax')->except('index');
    }
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return $this->datatable();
        }
        return view('user.cabang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('user.cabang.create');
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
    public function datatable(): JsonResponse
    {
        $model = $this->query();
        return DataTables::eloquent($model)->addIndexColumn()->addColumn('action', 'user.cabang.action')->make(true);
    }
    public function query()
    {
        return BarantinCabang::with('register:id,master_upt_id,pj_barantin_id,status,keterangan,registers.updated_at,blockir', 'negara:id,nama', 'provinsi:nama,id', 'kotas:nama,id')
            ->select('barantin_cabangs.id', 'email', 'nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'alamat', 'kota', 'barantin_cabangs.provinsi_id', 'negara_id', 'telepon', 'fax', 'status_import', 'user_id')
            ->where('pj_baratin_id', auth()->user()->baratin->id);
    }
}
