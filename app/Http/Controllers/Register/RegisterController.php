<?php

namespace App\Http\Controllers\Register;

use App\Models\Register;
use App\Models\PjBaratin;
use App\Models\PreRegister;
use App\Models\PjBaratanKpp;
use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
use App\Models\DokumenPendukung;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\RegisterRequestStore;
use App\Http\Requests\DokumenPendukungRequestStore;

class RegisterController extends Controller
{

    /* register  formulir handler */
    public function RegisterFormulirIndex(string $id): View
    {
        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        $baratan = PjBaratanKpp::where('email', $register->email)->first();
        if ($baratan) {
            return view('register.form.index', compact('id', 'baratan'));
        }
        return view('register.form.index', compact('id'));
    }
    /* register form request by ajax */
    public function RegisterForm(string $id): View
    {
        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        if ($register->pemohon === 'perusahaan') {
            return view('register.form.partial.perusahaan', compact('register'));
        }
        return view('register.form.partial.perorangan', compact('register'));
    }
    /* status register */
    public function StatusRegister(): View
    {
        return view('register.form.partial.status-register');
    }
    /* message register */
    public function RegisterMessage(): View
    {
        return view('register.message');
    }
    public function CheckRegister(mixed $register): RedirectResponse|bool
    {
        if (!$register || !$register->verify_email) {
            abort(redirect()->route('register.message')->with(['message_token' => 'Email tidak terverifikasi silahkan register ulang']));
        }
        if ($register->status == 'MENUNGGU') {
            abort(redirect()->route('register.message')->with(['message_waiting' => 'Data sedang di proses']));
        }
        if ($register->status == 'DITOLAK') {
            abort(redirect()->route('register.message')->with(['message_cancel' => 'Data ditolak silahkan register ulang']));
        }
        return true;
    }



    /* register saved */
    public function RegisterStore(RegisterRequestStore $request, string $id): JsonResponse
    {


        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        $dokumen = DokumenPendukung::where('pre_register_id', $id)->pluck('jenis_dokumen');
        // dd($dokumen);
        if ($register->pemohon === 'perusahaan') {
            /* perusahaan register cek */
            if ($dokumen->contains('KTP') && $dokumen->contains('PASSPORT') && $dokumen->contains('NPWP') && $dokumen->contains('SIUP') && $dokumen->contains('surat_keterangan_domisili') && $dokumen->contains('NIB')) {
                // $this->SaveRegister($request, $id);

                $this->SaveRegister($request, $id);
                return response()->json(['status' => true, 'message' => 'Register Berhasil Dilakukan'], 200);
            }
            return response()->json(['status' => false, 'message' => 'silahkan lengkapi dokumen KTP, PASSPORT, NPWP, SIUP / IUI / IUT / SIUP JPT, surat keterangan domisili, NIB'], 422);
        } else {
            /* perorangan register cek */
            if ($dokumen->contains('NPWP') && $dokumen->contains('KTP') && $dokumen->contains('PASSPORT')) {
                $this->SaveRegister($request, $id);
                return response()->json(['status' => true, 'message' => 'Register Berhasil Dilakukan'], 200);
            }
            return response()->json(['status' => false, 'message' => 'silahkan lengkapi dokumen KTP, NPWP, dan PASSPORT'], 422);
        }

    }
    /* for saved register */
    public function SaveRegister(Request $request, string $id): void
    {

        $data = $request->all();
        unset($data['upt'], $data['nomor_fax'], $data['negara'], $data['provinsi'], $data['kota'], $data['pemohon']);
        $data = collect($data);
        $data = $data->merge([
            'fax' => $request->nomor_fax,
            'negara_id' => $request->negara,
            'provinsi_id' => $request->provinsi,
            'nama_perusahaan' => $request->pemohon,
            'status' => 'MENUNGGU',
            'pre_register_id' => $id,
            'kota' => $request->kota
        ]);
        DB::transaction(function () use ($data, $request, $id) {
            $baratin = PjBaratin::create($data->all());
            foreach ($request->upt as $value) {
                Register::create(['master_upt_id' => $value, 'pj_barantin_id' => $baratin->id]);
            }
            DokumenPendukung::where('pre_register_id', $id)->update(['baratin_id' => $baratin->id, 'pre_register_id' => null]);
            PreRegister::find($id)->update(['status' => 'MENUNGGU']);
        });

        return;
    }

    /* register dokumen pendukung saved */
    public function DokumenPendukungStore(string $id, DokumenPendukungRequestStore $request): JsonResponse
    {
        $file = Storage::disk('public')->put('file_pendukung/' . $id, $request->file('file_dokumen'));
        $data = $request->only(['jenis_dokumen', 'nomer_dokumen', 'tanggal_terbit']);
        $data = collect($data)->merge(['pre_register_id' => $id, 'file' => $file]);

        $dokumen = DokumenPendukung::create($data->all());

        if ($dokumen) {
            return AjaxResponse::SuccessResponse('dokumen pendukung berhasil ditambah', 'datatable-dokumen-pendukung');
        }
        return AjaxResponse::ErrorResponse('dokumen pendukung gagal ditambah', 200);

    }

    /* dokumen pendukung datatable hanlder */
    public function DokumenPendukungDataTable(string $id): JsonResponse
    {
        $model = DokumenPendukung::where('pre_register_id', $id);

        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('action', 'register.form.partial.action_pendukung_datatable')
            ->editColumn('file', 'register.form.partial.file_pendukung_datatable')
            ->rawColumns(['action', 'file'])
            ->toJson();
    }
    public function DokumenPendukungDestroy(string $id): JsonResponse
    {

        $data = DokumenPendukung::find($id);
        $file = Storage::disk('public')->delete($data->file);
        $res = $data->delete();

        if ($res) {
            return AjaxResponse::SuccessResponse('dokumen pendukung berhasil dihapus', 'datatable-dokumen-pendukung');
        }
        return AjaxResponse::ErrorResponse('dokumen pendukung gagal dihapus', 200);
    }

}
