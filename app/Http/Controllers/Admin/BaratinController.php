<?php

namespace App\Http\Controllers\Admin;

use App\Models\PjBaratin;
use Illuminate\Http\Request;
use App\Models\DokumenPendukung;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BaratinController extends Controller
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
        return view('admin.baratin.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(string $id): View
    {
        $data = PjBaratin::with(['provinsi:nama,id', 'kotas:nama,id', 'negara:id,nama'])->find($id);
        return view('admin.baratin.show', compact('data'));
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
        $model = PjBaratin::with(['provinsi:nama,id', 'kotas:nama,id', 'negara:id,nama'])->select('pj_baratins.id', 'email', 'nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'alamat', 'kota', 'provinsi_id', 'negara_id', 'telepon', 'fax', 'status_import', 'status');

        return DataTables::eloquent($model)->addIndexColumn()->addColumn('action', 'admin.baratin.action')->make(true);
    }
    public function datatablePendukung(string $id): JsonResponse
    {
        $model = DokumenPendukung::where('pj_baratin_id', $id);

        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->editColumn('file', 'register.form.partial.file_pendukung_datatable')
            ->rawColumns(['action', 'file'])
            ->toJson();
    }
}
