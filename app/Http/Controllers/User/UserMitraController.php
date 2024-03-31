<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
use App\Models\MitraPerusahaan;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\UserMitraRequestStore;
use App\Http\Requests\UserMitraRequestUpdate;

class UserMitraController extends Controller
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
        return view('user.mitra.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('user.mitra.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(UserMitraRequestStore $request)
    {
        $data = $request->merge([
            'master_negara_id' => $request->negara,
            'master_provinsi_id' => $request->provinsi,
            'master_kota_kab_id' => $request->kabupaten_kota,
            'pj_baratin_id' => auth()->user()->baratin->id ?? null,
            'barantin_cabang_id' => auth()->user()->baratincabang->id ?? null
        ])->except('negara', 'provinsi', 'kabupaten_kota');

        $res = MitraPerusahaan::create($data);
        if ($res) {
            return AjaxResponse::SuccessResponse('Mitra berhasil ditambah', 'user-mitra-datatable');
        }
        return AjaxResponse::ErrorResponse('Mitra gagal ditambah', 400);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = MitraPerusahaan::find($id);
        return view('user.mitra.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = MitraPerusahaan::find($id);
        return view('user.mitra.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserMitraRequestUpdate $request, string $id)
    {
        $data = $request->merge([
            'master_negara_id' => $request->negara,
            'master_provinsi_id' => $request->provinsi,
            'master_kota_kab_id' => $request->kabupaten_kota,
        ])->except('negara', 'provinsi', 'kabupaten_kota', '_method');

        $res = MitraPerusahaan::find($id)->update($data);
        if ($res) {
            return AjaxResponse::SuccessResponse('Mitra berhasil diupdate', 'user-mitra-datatable');
        }
        return AjaxResponse::ErrorResponse('Mitra gagal diupdate', 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $res = MitraPerusahaan::destroy($id);
        if ($res) {
            return AjaxResponse::SuccessResponse('Mitra berhasil dihapus', 'user-mitra-datatable');
        }
        return AjaxResponse::ErrorResponse('Mitra gagal dihapus', 400);
    }
    public function datatable(): JsonResponse
    {
        $model = $this->query();
        return DataTables::eloquent($model)->addIndexColumn()->addColumn('action', 'user.mitra.action')->make(true);
    }
    public function query()
    {
        $select = MitraPerusahaan::with(['negara:id,nama', 'provinsi:id,nama', 'kotas:id,nama'])->select(
            'mitra_perusahaans.id',
            'nama_mitra',
            'jenis_identitas_mitra',
            'nomor_identitas_mitra',
            'alamat_mitra',
            'telepon_mitra',
            'master_negara_id',
            'master_provinsi_id',
            'master_kota_kab_id',
        );
        if (auth()->user()->role === 'cabang') {
            return $select->where('barantin_cabang_id', auth()->user()->baratincabang->id);
        }
        return $select->where('pj_baratin_id', auth()->user()->baratin->id);

    }
}
