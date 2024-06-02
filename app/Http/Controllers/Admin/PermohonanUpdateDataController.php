<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
use App\Models\PengajuanUpdatePj;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailPenolakanUpdateData;
use App\Mail\MailSendLinkForUpdatePj;
use Yajra\DataTables\Facades\DataTables;

class PermohonanUpdateDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('ajax')->except('index');
    }
    public function index()
    {
        if (request()->ajax()) {
            return $this->datatable();
        }
        return view('admin.permohonan-update.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function confirmUpdate(Request $request, string $pengajuan_id)
    {
        $request->validate([
            'persetujuan' => 'required|in:disetujui,ditolak'
        ]);

        $data = PengajuanUpdatePj::find($pengajuan_id);
        $namaPerusahaan = $data->baratin->nama_perusahaan ?? $data->barantincabang->nama_perusahaan ?? null;
        $email = $data->baratin->email ?? $data->barantincabang->email ?? null;
        if ($request->persetujuan === 'ditolak') {
            $data->update(['persetujuan' => 'ditolak', 'status_update' => 'gagal']);
            Mail::to($email)->send(new MailPenolakanUpdateData);
            return AjaxResponse::SuccessResponse("Perubahan data {$namaPerusahaan} ditolak", 'permohonan-update-datatable');
        }
        $token = md5($namaPerusahaan . now() . env('UPDATE_TOKEN_KEY', 'B4rantinK3yS3Cret'));
        $data->update(['persetujuan' => 'disetujui', 'update_token' => $token, 'expire_at' => now()->addDay()]);
        $idBarantin = $data->baratin->id ?? $data->barantincabang->id ?? null;
        Mail::to($email)->send(new MailSendLinkForUpdatePj($idBarantin, $token));
        return AjaxResponse::SuccessResponse("Perubahan data {$namaPerusahaan} disetujui", 'permohonan-update-datatable');
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
    public function datatable()
    {
        $model = PengajuanUpdatePj::with(
            'baratin:id,pre_register_id,nama_perusahaan,jenis_perusahaan',
            'baratin.preregister:id,pemohon,jenis_perusahaan',
            'barantincabang:id,pre_register_id,nama_perusahaan,jenis_perusahaan',
            'barantincabang.preregister:id,pemohon,jenis_perusahaan'
        )->select('pengajuan_update_pjs.*');
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', 'admin.permohonan-update.action')
            ->addColumn('nama_perusahaan', function ($model) {
                return $model->baratin->nama_perusahaan ?? $model->barantincabang->nama_perusahaan ?? null;
            })
            ->filterColumn('nama_perusahaan', function ($query, $keyword) {
                $query->whereHas('baratin', function ($query) use ($keyword) {
                    $query->where('nama_perusahaan', 'LIKE', "%{$keyword}%");
                })->orWhereHas('barantincabang', function ($query) use ($keyword) {
                    $query->where('nama_perusahaan', 'LIKE', "%{$keyword}%");
                });
            })
            ->addColumn('jenis_perusahaan', function ($model) {
                return $model->baratin->jenis_perusahaan ?? $model->barantincabang->jenis_perusahaan ?? null;
            })
            ->filterColumn('jenis_perusahaan', function ($query, $keyword) {
                $query->whereHas('baratin', function ($query) use ($keyword) {
                    $query->where('jenis_perusahaan', 'LIKE', "%{$keyword}%");
                })->orWhereHas('barantincabang', function ($query) use ($keyword) {
                    $query->where('jenis_perusahaan', 'LIKE', "%{$keyword}%");
                });
            })
            ->addColumn('pemohon', function ($model) {
                return $model->baratin->preregister->pemohon ?? $model->barantincabang->preregister->pemohon ?? null;
            })
            ->filterColumn('pemohon', function ($query, $keyword) {
                $query->whereHas('baratin.preregister', function ($query) use ($keyword) {
                    $query->where('pemohon', 'LIKE', "%{$keyword}%");
                })->orWhereHas('barantincabang.preregister', function ($query) use ($keyword) {
                    $query->where('pemohon', 'LIKE', "%{$keyword}%");
                });
            })
            ->addColumn('perusahaan_pemohon', function ($model) {
                return $model->baratin->preregister->jenis_perusahaan ?? $model->barantincabang->preregister->jenis_perusahaan ?? null;
            })
            ->filterColumn('perusahaan_pemohon', function ($query, $keyword) {
                $query->whereHas('baratin.preregister', function ($query) use ($keyword) {
                    $query->where('jenis_perusahaan', 'LIKE', "%{$keyword}%");
                })->orWhereHas('barantincabang.preregister', function ($query) use ($keyword) {
                    $query->where('jenis_perusahaan', 'LIKE', "%{$keyword}%");
                });
            })
            ->make(true);
    }
}
