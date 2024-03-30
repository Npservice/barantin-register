<?php

namespace App\Http\Controllers\User;

use App\Models\Ppjk;
use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\UserPppjkRequestStore;
use App\Http\Requests\UserPppjkRequestUpdate;

class UserPpjkController extends Controller
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
        return view('user.ppjk.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('user.ppjk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserPppjkRequestStore $request)
    {

        $data = $request->merge([
            'master_negara_id' => 99,
            'master_provinsi_id' => $request->provinsi,
            'master_kota_kab_id' => $request->kabupaten_kota,
            'pj_baratin_id' => auth()->user()->baratin->id ?? null,
            'barantin_cabang_id' => auth()->user()->baratincabang->id ?? null,
        ])->except(['provinsi', 'kabupaten_kota']);
        $res = Ppjk::create($data);
        if ($res) {
            return AjaxResponse::SuccessResponse('Ppjk berhasil ditambah', 'user-ppjk-datatable');
        }
        return AjaxResponse::ErrorResponse('Ppjk gagal ditambah', 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Ppjk::find($id);
        return view('user.ppjk.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Ppjk::find($id);
        return view('user.ppjk.edit', compact('data'));


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserPppjkRequestUpdate $request, string $id)
    {
        $data = $request->merge([
            'master_provinsi_id' => $request->provinsi,
            'master_kota_kab_id' => $request->kabupaten_kota,
        ])->except(['provinsi', 'kabupaten_kota', '_method']);

        $res = Ppjk::find($id)->update($data);

        if ($res) {
            return AjaxResponse::SuccessResponse('Ppjk berhasil diupdate', 'user-ppjk-datatable');
        }
        return AjaxResponse::ErrorResponse('Ppjk gagal diupdate', 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $res = Ppjk::destroy($id);
        if ($res) {
            return AjaxResponse::SuccessResponse('Ppjk berhasil dihapus', 'user-ppjk-datatable');
        }
        return AjaxResponse::ErrorResponse('Ppjk gagal dihapus', 400);
    }
    public function datatable(): JsonResponse
    {
        $model = $this->query();
        return DataTables::eloquent($model)->addIndexColumn()->addColumn('action', 'user.ppjk.action')->make(true);
    }
    public function query()
    {
        $select = Ppjk::with(['negara:id,nama', 'provinsi:id,nama', 'kotas:id,nama'])->select(
            'ppjks.id',
            'nama_ppjk',
            'email_ppjk',
            'tanggal_kerjasama_ppjk',
            'alamat_ppjk',
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

