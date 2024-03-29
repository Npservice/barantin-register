<?php

namespace App\Http\Controllers\User;

use App\Models\Register;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class UserUptController extends Controller
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
        return view('user.upt.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('user.upt.create');
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
        //
    }
    public function datatable(): JsonResponse
    {
        $model = $this->query();

        return DataTables::eloquent($model)->addIndexColumn()->make(true);
    }
    // query model
    public function query()
    {
        if (auth()->user()->role === 'cabang') {
            return Register::with('upt:nama,id')->whereHas('baratincabang', function ($query) {
                $query->where('id', auth()->user()->baratincabang->id);
            })->select('register.id', 'barantin_cabang_id', 'register.status', 'register.keterangan', 'master_upt_id', 'blockir', 'registers.updated_at');
        }
        return Register::with('upt:nama,id')->whereHas('baratin', function ($query) {
            $query->where('id', auth()->user()->baratin->id);
        })->select('registers.id', 'pj_barantin_id', 'registers.status', 'registers.keterangan', 'master_upt_id', 'blockir', 'registers.updated_at');
    }
}
