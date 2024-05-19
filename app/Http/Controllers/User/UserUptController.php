<?php

namespace App\Http\Controllers\User;

use App\Rules\UptRule;
use App\Models\Register;
use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
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
        /* get upt yang telah dipilih */
        $register = Register::where(function ($query) {
            $query->where('pj_barantin_id', auth()->user()->baratin->id ?? null)->orWhere('barantin_cabang_id', auth()->user()->baratincabang->id ?? null);
        })->pluck('master_upt_id')->toArray();
        // validasi upt
        $request->validate([
            'upt' => [
                'required',
                new UptRule,
            ],
        ]);
        foreach ($request->upt as $value) {
            if (in_array($value, $register)) {
                $res = Register::where('master_upt_id', $value)->where(function ($query) {
                    $query->where('pj_barantin_id', auth()->user()->baratin->id ?? null)->orWhere('barantin_cabang_id', auth()->user()->baratincabang->id ?? null);
                })->update(['status' => 'MENUNGGU']);
            } else {
                $res = Register::create(['master_upt_id' => $value, 'pj_barantin_id' => auth()->user()->baratin->id ?? null, 'barantin_cabang_id' => auth()->user()->baraticabang->id ?? null, 'status' => 'MENUNGGU', 'pre_register_id' => auth()->user()->baratin->pre_register_id]);
            }
        }
        if ($res) {
            return AjaxResponse::SuccessResponse('Upt berhasil diajukan', 'user-upt-datatable');
        }
        return AjaxResponse::ErrorResponse('Upt gagal diajukan', 400);

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
            })->select('register.id', 'barantin_cabang_id', 'register.status', 'register.keterangan', 'master_upt_id', 'blockir', 'registers.updated_at', 'registers.created_at');
        }
        return Register::with('upt:nama,id')->whereHas('baratin', function ($query) {
            $query->where('id', auth()->user()->baratin->id);
        })->select('registers.id', 'pj_barantin_id', 'registers.status', 'registers.keterangan', 'master_upt_id', 'blockir', 'registers.updated_at', 'registers.created_at');
    }
}
