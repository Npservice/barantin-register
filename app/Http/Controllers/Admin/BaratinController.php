<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Register;
use App\Models\PjBaratin;
use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
use App\Models\DokumenPendukung;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Builder;

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
    public function show(Request $request, string $id): View
    {
        $data = PjBaratin::with(['provinsi:nama,id', 'kotas:nama,id', 'negara:id,nama'])->find($id);
        $register_id = $request->register_id;
        return view('admin.baratin.show', compact('data', 'register_id'));
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
        /* get id upt bedasarkan user yang login */
        $uptId = auth()->guard('admin')->user()->upt_id;
        /* cek user apakah mempunyai id upt */
        if ($uptId) {
            $model = $this->QueryRegister()->where('master_upt_id', $uptId);
        } else {
            $model = $this->QueryRegister();
        }

        return DataTables::eloquent($model)->addIndexColumn()
            ->filterColumn('updated_at', function ($query, $keyword) {
                $range = explode(' - ', $keyword);
                if (count($range) === 2) {
                    $startDate = Carbon::parse($range[0])->startOfDay();
                    $endDate = Carbon::parse($range[1])->endOfDay();
                    $query->whereBetween('registers.updated_at', [$startDate, $endDate]);
                }

            })
            ->addColumn('action', 'admin.baratin.action')->make(true);
    }
    public function QueryRegister(): Builder
    {
        return Register::with([
            'upt:nama,id',
            'baratin' => function ($query) {
                $query->with(['kotas:nama,id', 'negara:id,nama', 'provinsi:nama,id'])
                    ->select('id', 'email', 'nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'alamat', 'kota', 'provinsi_id', 'negara_id', 'telepon', 'fax', 'status_import');
            }
        ])->select('registers.id', 'master_upt_id', 'pj_barantin_id', 'status', 'keterangan', 'registers.created_at')->whereNotNull('pj_barantin_id');


    }
    public function confirmRegister(string $id, Request $request): JsonResponse
    {
        $request->validate(['status' => 'required|in:DISETUJUI,DITOLAK', 'keterangan' => 'nullable']);
        /* find register by id */
        $register = Register::find($id);
        /* cek register  */
        if ($register) {
            $res = $register->update($request->all());
            if ($res) {
                return AjaxResponse::SuccessResponse('data register ' . $request->status, 'baratin-datatable');
            }
            return AjaxResponse::ErrorResponse('register gagal di aprove', 400);
        }
        return AjaxResponse::ErrorResponse('data register tidak ditemukan', 400);
    }
    public function datatablePendukung(string $id): JsonResponse
    {
        $model = DokumenPendukung::where('baratin_id', $id);

        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->editColumn('file', 'register.form.partial.file_pendukung_datatable')
            ->rawColumns(['action', 'file'])
            ->toJson();
    }
}
