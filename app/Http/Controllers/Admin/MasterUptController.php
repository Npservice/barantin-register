<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MasterUptRequestStore;
use App\Http\Requests\MasterUptRequestUpdate;
use App\Models\MasterUpt;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

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
    public function update(MasterUptRequestUpdate $request, string $id)
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
    static function datatable(): JsonResponse
    {
        $model = MasterUpt::query();

        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', 'admin.master.upt.action')
            ->toJson();
    }
}
