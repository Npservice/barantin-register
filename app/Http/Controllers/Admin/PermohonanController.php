<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Register;
use App\Models\PjBaratin;
use App\Models\PreRegister;
use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
use App\Models\BarantinCabang;
use App\Models\DokumenPendukung;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Builder;

class PermohonanController extends Controller
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
        return view('admin.permohonan.index');
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
    public function show(Request $request, string $id)//: View
    {
        $data = PjBaratin::with(['provinsi:nama,id', 'kotas:nama,id', 'negara:id,nama'])->find($id) ?? BarantinCabang::with(['provinsi:nama,id', 'kotas:nama,id', 'negara:id,nama', 'baratininduk:nama_perusahaan,id'])->find($id);
        $register = Register::find($request->register_id);
        $preregister = PreRegister::find($data->pre_register_id);
        if ($preregister->pemohon === 'perorangan') {
            return view('admin.permohonan.show.perorangan', compact('data', 'register'));
        } else {
            if ($preregister->jenis_perusahaan === 'induk' || !$preregister->jenis_perusahaan) {
                return view('admin.permohonan.show.induk', compact('data', 'register'));
            }
            return view('admin.permohonan.show.cabang', compact('data', 'register'));
        }
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
            return AjaxResponse::SuccessResponse('permohonan berhasil dihapus', 'permohonan-datatable');
        }
        return AjaxResponse::ErrorResponse($res, 400);

    }
    public function datatablePeoranganPerusahaanInduk(string $datatable): JsonResponse
    {
        /* get id upt bedasarkan user yang login */
        $uptId = auth()->guard('admin')->user()->upt_id;
        /* cek user apakah mempunyai id upt */
        if ($uptId) {
            $model = $this->QueryRegisterPeoranganAndInduk()->where('master_upt_id', $uptId)->whereHas('preregister', function ($query) use ($datatable) {
                $query->where('pemohon', $datatable);
            });
        } else {
            $model = $this->QueryRegisterPeoranganAndInduk()->whereHas('preregister', function ($query) use ($datatable) {
                $query->where('pemohon', $datatable);
            });
        }

        return DataTables::eloquent($model)->addIndexColumn()
            ->addColumn('upt', function ($row) {
                return $row->upt->nama_satpel . ' - ' . $row->upt->nama;
            })
            ->filterColumn('updated_at', function ($query, $keyword) {
                $range = explode(' - ', $keyword);
                if (count($range) === 2) {
                    $startDate = Carbon::parse($range[0])->startOfDay();
                    $endDate = Carbon::parse($range[1])->endOfDay();
                    $query->whereBetween('registers.updated_at', [$startDate, $endDate]);
                }

            })
            ->filterColumn('upt', function ($query, $keyword) {
                $query->whereHas('upt', function ($subquery) use ($keyword) {
                    $subquery->where('nama', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('nama_satpel', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('id', $keyword);
                });

            })
            ->addColumn('action', 'admin.permohonan.action.induk')->make(true);
    }
    /* query master datatable */
    public function QueryRegisterPeoranganAndInduk(): Builder
    {
        return Register::with([
            'upt:nama,id,nama_satpel',
            'baratin.kotas:nama,id',
            'baratin.negara:id,nama',
            'baratin.provinsi:id,nama',
            'baratin' => function ($query) {
                $query->select('id', 'email', 'nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'alamat', 'kota', 'provinsi_id', 'negara_id', 'telepon', 'fax', 'status_import', 'user_id');
            }
        ])->select('registers.id', 'master_upt_id', 'pj_barantin_id', 'status', 'keterangan', 'registers.updated_at', 'blockir', 'registers.pre_register_id')->whereNotNull('pj_barantin_id')->whereNot('registers.status', 'DISETUJUI');


    }

    public function datatableCabang(): JsonResponse
    {
        /* get id upt bedasarkan user yang login */
        $uptId = auth()->guard('admin')->user()->upt_id;
        /* cek user apakah mempunyai id upt */
        if ($uptId) {
            $model = $this->QueryRegisterCabang()->where('master_upt_id', $uptId)->whereHas('preregister', function ($query) {
                $query->where('pemohon', 'perusahaan')->where('jenis_perusahaan', 'cabang');
            });
        } else {
            $model = $this->QueryRegisterCabang()->whereHas('preregister', function ($query) {
                $query->where('pemohon', 'perusahaan')->where('jenis_perusahaan', 'cabang');
            });
        }
        // return response()->json($model->get());
        return DataTables::eloquent($model)->addIndexColumn()
            ->addColumn('upt', function ($row) {
                return $row->upt->nama_satpel . ' - ' . $row->upt->nama;
            })
            ->filterColumn('updated_at', function ($query, $keyword) {
                $range = explode(' - ', $keyword);
                if (count($range) === 2) {
                    $startDate = Carbon::parse($range[0])->startOfDay();
                    $endDate = Carbon::parse($range[1])->endOfDay();
                    $query->whereBetween('registers.updated_at', [$startDate, $endDate]);
                }

            })
            ->filterColumn('upt', function ($query, $keyword) {
                $query->whereHas('upt', function ($subquery) use ($keyword) {
                    $subquery->where('nama', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('nama_satpel', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('id', $keyword);
                });

            })
            ->addColumn('action', 'admin.permohonan.action.cabang')->make(true);
    }


    public function QueryRegisterCabang(): Builder
    {
        return Register::with([
            'upt:nama,id,nama_satpel',
            'baratincabang.baratininduk:nama_perusahaan,id',
            'baratincabang.kotas:nama,id',
            'baratincabang.negara:id,nama',
            'baratincabang.provinsi:id,nama',
            'baratincabang' => function ($query) {
                $query->select('id', 'email', 'nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'alamat', 'kota', 'provinsi_id', 'negara_id', 'telepon', 'fax', 'status_import', 'user_id', 'nitku', 'pj_baratin_id');
            }
        ])->select('registers.id', 'master_upt_id', 'barantin_cabang_id', 'status', 'keterangan', 'registers.updated_at', 'blockir', 'registers.pre_register_id')->whereNotNull('barantin_cabang_id')->whereNot('registers.status', 'DISETUJUI');


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
                return AjaxResponse::SuccessResponse('data register ' . $request->status, 'permohonan-datatable');
            }
            return AjaxResponse::ErrorResponse('register gagal di aprove', 400);
        }
        return AjaxResponse::ErrorResponse('data register tidak ditemukan', 400);
    }
    public function datatablePendukung(string $id): JsonResponse
    {
        $model = $model = DokumenPendukung::where('baratin_id', $id)->orWhere('barantin_cabang_id', $id);

        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->editColumn('file', 'register.form.partial.file_pendukung_datatable')
            ->rawColumns(['action', 'file'])
            ->toJson();
    }
}
