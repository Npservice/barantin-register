<?php

namespace App\Http\Controllers\User;

use App\Models\PjBaratin;
use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
use App\Models\BarantinCabang;
use App\Models\DokumenPendukung;
use App\Models\PengajuanUpdatePj;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\RequestUpdatePerorangan;
use App\Http\Requests\DokumenPendukungRequestStore;
use App\Http\Requests\RequestUpdatePerusahaanInduk;
use App\Http\Requests\RequestUpdateDokumenPendukung;
use App\Http\Requests\RequestUpdatePerusahaanCabang;

class UpdatePenggunaJasaController extends Controller
{
    public function __construct()
    {
        $this->middleware('ajax')->except(['Message', 'UpdateIndex']);
    }
    public function Message(): View
    {
        return view('register.message');
    }
    public function UpdateIndex(string $barantin_id, string $token): View
    {
        return view('user.update.index', compact('barantin_id', 'token'));
    }
    public function UpdateForm(string $barantin_id, string $token): View
    {
        $data = PengajuanUpdatePj::where('update_token', $token)->where(function ($query) use ($barantin_id) {
            $query->where('pj_baratin_id', $barantin_id)->orWhere('barantin_cabang_id', $barantin_id);
        })->where('persetujuan', 'disetujui')->first();
        $view = isset($data->baratin->preregister) ? ($data->baratin->preregister->pemohon === 'perusahaan' ? 'user.update.partial.induk' : 'user.update.partial.perorangan') : 'user.update.partial.cabang';
        return view($view, compact('data'));
    }

    public function StoreRegisterPerorangan(RequestUpdatePerorangan $request, string $id)
    {
        $dokumen = DokumenPendukung::where('baratin_id', $id)->pluck('jenis_dokumen');
        if ($dokumen->contains('KTP') || $dokumen->contains('PASSPORT')) {
            $data = $request->all();
            unset($data['upt'], $data['nomor_fax'], $data['negara'], $data['provinsi'], $data['kota'], $data['pemohon'], $data['lingkup_aktifitas']);
            $data = collect($data);
            $data = $data->merge([
                'fax' => $request->nomor_fax,
                'negara_id' => 99,
                'provinsi_id' => $request->provinsi,
                'nama_perusahaan' => $request->pemohon,
                'kota' => $request->kota,
                'lingkup_aktifitas' => implode(',', $request->lingkup_aktivitas),
            ]);
            DB::transaction(function () use ($data, $id, $request) {
                $pjBaratin = PjBaratin::find($id);
                $pjBaratin->update($data->all());
                $pjBaratin->preregister()->update(['email' => $request->email]);
                PengajuanUpdatePj::where('pj_baratin_id', $id)->where('status_update', 'proses')->update(['status_update' => 'selesai']);
            });
            return response()->json(['status' => true, 'message' => 'Update Perorangan Berhasil Dilakukan'], 200);
        }
        return response()->json(['status' => false, 'message' => 'silahkan lengkapi dokumen KTP/PASSPORT'], 422);
    }
    public function StoreRegisterPerusahaanInduk(RequestUpdatePerusahaanInduk $request, string $id)
    {

        $dokumen = DokumenPendukung::where('baratin_id', $id)->pluck('jenis_dokumen');
        if ($dokumen->contains('NPWP') && $dokumen->contains('NIB')) {
            $data = $request->all();
            unset($data['upt'], $data['nomor_fax'], $data['negara'], $data['provinsi'], $data['kota'], $data['pemohon'], $data['nitku'], $data['lingkup_aktifitas']);
            $data = collect($data);
            $data = $data->merge([
                'fax' => $request->nomor_fax,
                'negara_id' => 99,
                'nitku' => $request->nitku ?? '000000',
                'nama_alias_perusahaan' => $request->nama_alias_perusahaan,
                'provinsi_id' => $request->provinsi,
                'nama_perusahaan' => $request->pemohon,
                'kota' => $request->kota,
                'lingkup_aktifitas' => implode(',', $request->lingkup_aktivitas),
            ]);

            DB::transaction(function () use ($data, $id, $request) {
                $pjBaratin = PjBaratin::find($id);
                $pjBaratin->update($data->all());
                $pjBaratin->preregister()->update(['email' => $request->email]);
                PengajuanUpdatePj::where('pj_baratin_id', $id)->where('status_update', 'proses')->update(['status_update' => 'selesai']);
            });
            return response()->json(['status' => true, 'message' => 'Update Perusahaan induk Berhasil Dilakukan'], 200);
        }
        return response()->json(['status' => false, 'message' => 'silahkan lengkapi dokumen  NPWP, NIB'], 422);
    }
    public function StoreRegisterPerusahaanCabang(RequestUpdatePerusahaanCabang $request, string $id)
    {

        $dokumen = DokumenPendukung::where('barantin_cabang_id', $id)->pluck('jenis_dokumen');

        if ($dokumen->contains('NITKU')) {
            $data = $request->all();
            unset($data['upt'], $data['nomor_fax'], $data['negara'], $data['provinsi'], $data['kota'], $data['pemohon'], $data['lingkup_aktifitas']);
            $data = collect($data);
            $data = $data->merge([
                'fax' => $request->nomor_fax,
                'negara_id' => 99,
                'provinsi_id' => $request->provinsi,
                'nama_perusahaan' => $request->pemohon,
                'kota' => $request->kota,
                'lingkup_aktifitas' => implode(',', $request->lingkup_aktivitas),
            ]);
            DB::transaction(function () use ($data, $id, $request) {
                $cabang = BarantinCabang::find($id);
                $cabang->update($data->all());
                $cabang->preregister()->update(['email' => $request->email]);
                PengajuanUpdatePj::where('barantin_cabang_id', $id)->where('status_update', 'proses')->update(['status_update' => 'selesai']);
            });
            return response()->json(['status' => true, 'message' => 'Update Perusahaan cabang Berhasil Dilakukan'], 200);
        }
        return response()->json(['status' => false, 'message' => 'silahkan lengkapi dokumen  NITKU'], 422);
    }

    public function DokumenPendukungStore(string $id, RequestUpdateDokumenPendukung $request): JsonResponse
    {
        $file = Storage::disk('public')->put('file_pendukung/' . $id, $request->file('file_dokumen'));
        $data = $request->only(['jenis_dokumen', 'nomer_dokumen', 'tanggal_terbit']);
        $data = collect($data)->merge([$request->jenis_perusahaan === 'cabang' ? 'barantin_cabang_id' : 'baratin_id' => $id, 'file' => $file]);

        $dokumen = DokumenPendukung::create($data->all());

        if ($dokumen) {
            return AjaxResponse::SuccessResponse('dokumen pendukung berhasil ditambah', 'datatable-dokumen-pendukung');
        }
        return AjaxResponse::ErrorResponse('dokumen pendukung gagal ditambah', 200);
    }


    public function DokumenPendukungDataTable(string $id): JsonResponse
    {
        $model = DokumenPendukung::where('baratin_id', $id)->orWhere('barantin_cabang_id', $id);

        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', 'user.update.partial.action_pendukung_datatable')
            ->editColumn('file', 'user.update.partial.file_pendukung_datatable')
            ->rawColumns(['action', 'file'])
            ->toJson();
    }

    public function DokumenPendukungDestroy(string $id): JsonResponse
    {

        $data = DokumenPendukung::find($id);
        Storage::disk('public')->delete($data->file);
        $res = $data->delete();

        if ($res) {
            return AjaxResponse::SuccessResponse('dokumen pendukung berhasil dihapus', 'datatable-dokumen-pendukung');
        }
        return AjaxResponse::ErrorResponse('dokumen pendukung gagal dihapus', 200);
    }
}
