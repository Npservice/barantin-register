<?php

namespace App\Http\Controllers\Admin;

use App\Models\Register;
use App\Models\PjBaratin;
use App\Models\PreRegister;
use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Builder;

class PemohonController extends Controller
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
        return view('admin.pemohon.index');
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
        $res = Register::destroy($id);
        if ($res) {
            return AjaxResponse::SuccessResponse('pemohon berhasil dihapus', 'pemohon-datatable');
        }
        return AjaxResponse::ErrorResponse($res, 400);

    }
    public function datatable(): JsonResponse
    {
        if (auth()->guard('admin')->user()->upt_id) {
            $model = $this->QueryPemohon()->where('master_upt_id', auth()->guard('admin')->user()->upt_id);
        } else {
            $model = $this->QueryPemohon();
        }

        return DataTables::eloquent($model)->addIndexColumn()->addColumn('action', 'admin.pemohon.action')->make(true);

    }
    /* query master datatable */
    public function QueryPemohon(): Builder
    {
        return Register::with('preregister:id,pemohon,nama,email,pemohon', 'upt:nama,id')
            ->select('registers.id', 'registers.created_at', 'status', 'keterangan', 'pre_register_id', 'master_upt_id');
    }
}
