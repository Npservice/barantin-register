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
        $namaPerusahaan = $data->barantin->nama_perusahaan ?? null;
        $email = $data->barantin->email ?? null;
        if ($request->persetujuan === 'ditolak') {
            $data->update(['persetujuan' => 'ditolak', 'status_update' => 'gagal']);
            Mail::to($email)->send(new MailPenolakanUpdateData);
            return AjaxResponse::SuccessResponse("Perubahan data {$namaPerusahaan} ditolak", 'permohonan-update-datatable');
        }
        $token = md5($namaPerusahaan . now() . env('UPDATE_TOKEN_KEY', 'B4rantinK3yS3Cret'));
        $data->update(['persetujuan' => 'disetujui', 'update_token' => $token, 'expire_at' => now()->addDay()]);
        $idBarantin = $data->barantin->id ?? $data->barantincabang->id ?? null;
        Mail::to($email)->send(new MailSendLinkForUpdatePj($idBarantin, $token));
        return AjaxResponse::SuccessResponse("Perubahan data {$namaPerusahaan} disetujui", 'permohonan-update-datatable');
    }
    public function datatable()
    {
        $model = PengajuanUpdatePj::with(
            'barantin:id,pre_register_id,nama_perusahaan,jenis_perusahaan',
            'barantin.preregister:id,pemohon,jenis_perusahaan',
        )->select('pengajuan_update_pjs.*');
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', 'admin.permohonan-update.action')
            ->make(true);
    }
}
