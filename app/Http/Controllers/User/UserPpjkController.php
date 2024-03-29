<?php

namespace App\Http\Controllers\User;

use App\Models\Ppjk;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

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
    public function store(Request $request)
    {
        //
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

