<?php

namespace App\Http\Controllers\Admin;

use App\Models\MasterUpt;
use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\MasterUptRequestStore;
use App\Http\Requests\MasterUptRequestUpdate;

class MasterUptController extends Controller
{
    public function __construct()
    {
        $this->middleware('ajax')->except('index');
    }
    public function index(): JsonResponse|View
    {
        if (request()->ajax()) {
            return $this->datatable();
        }
        return view('admin.master.upt.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.master.upt.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MasterUptRequestStore $request): JsonResponse
    {
        $res = MasterUpt::create($request->all());
        if ($res) {
            return AjaxResponse::SuccessResponse('master upt berhasil ditambah', 'masterupt-datatable');
        }
        return AjaxResponse::ErrorResponse('master upt gagal ditambah', 400);
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
    public function edit(string $id): View
    {
        $data = MasterUpt::find($id);
        return view('admin.master.upt.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MasterUptRequestUpdate $request, string $id): JsonResponse
    {

        $res = MasterUpt::find($id)->update($request->all());
        if ($res) {
            return AjaxResponse::SuccessResponse('master upt berhasil diupdate', 'masterupt-datatable');
        }
        return AjaxResponse::ErrorResponse('master upt gagal diupdate', 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $res = MasterUpt::destroy($id);
        if ($res) {
            return AjaxResponse::SuccessResponse('master upt berhasil dihapus', 'masterupt-datatable');
        }
        return AjaxResponse::ErrorResponse('master upt gagal dihapus', 400);
    }
    static function datatable(): JsonResponse
    {
        $model = MasterUpt::query();

        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->editColumn('stat_ppkol', function ($row) {
                return $row->stat_ppkol === 'Y' ? '<center>YA</center>' : '<center>TIDAK</center>';
            })
            ->editColumn('stat_insw', function ($row) {
                return $row->stat_insw === 'Y' ? '<center>YA</center>' : '<center>TIDAK</center>';
            })
            ->addColumn('action', 'admin.master.upt.action')
            ->rawColumns(['action', 'stat_ppkol', 'stat_insw'])
            ->toJson();
    }
}
