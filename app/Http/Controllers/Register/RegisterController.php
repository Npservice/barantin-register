<?php

namespace App\Http\Controllers\Register;

use App\Models\Register;
use App\Models\PjBaratin;
use App\Models\PreRegister;
use App\Models\PjBaratanKpp;
use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
use App\Models\BarantinCabang;
use App\Models\DokumenPendukung;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\DokumenPendukungRequestStore;
use App\Http\Requests\RegisterRequesPerorangantStore;
use App\Http\Requests\RegisterRequestPerusahaanIndukStore;
use App\Http\Requests\RegisterRequestPerusahaanCabangStore;

class RegisterController extends Controller
{

    /* register  formulir handler */
    public function RegisterFormulirIndex(string $id): View
    {
        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        $baratan = PjBaratanKpp::where('email', $register->email)->value('id');

        if ($baratan) {
            return view('register.form.index', compact('id', 'baratan'));
        }
        return view('register.form.index', compact('id'));
    }
    /* register form request by ajax */
    public function RegisterForm(Request $request, string $id): View
    {
        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        $baratan_cek = PjBaratanKpp::find($request->baratan_id);
        $baratan = $baratan_cek ?? null;
        if ($register->pemohon === 'perusahaan') {
            if ($register->jenis_perusahaan === 'cabang') {
                $induk = PjBaratin::select('nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'id')->find($register->pj_baratin_id);
                return view('register.form.partial.cabang', compact('register', 'baratan', 'induk'));
            }
            return view('register.form.partial.induk', compact('register', 'baratan'));
        }
        return view('register.form.partial.perorangan', compact('register', 'baratan'));
    }
    /* status register */
    public function StatusRegister(): View|JsonResponse
    {
        if (request()->ajax()) {
            $model = Register::with([
                'upt:nama,id',
                'preregister:nama,id',
                'baratin' => function ($query) {
                    $query->with(['kotas:nama,id'])
                        ->select('nama_perusahaan', 'kota', 'nama_tdd', 'jabatan_tdd', 'id');
                }
            ])
                ->select('registers.id', 'master_upt_id', 'pj_barantin_id', 'status', 'keterangan', 'pre_register_id', 'updated_at')
                ->whereNotNull('pj_barantin_id')
                ->whereNotNull('status')
                ->orderBy('created_at', 'DESC');
            return DataTables::eloquent($model)->addIndexColumn()->toJson();
        }
        return view('register.status.index');
    }
    /* message register */
    public function RegisterMessage(): View
    {
        return view('register.message');
    }

    /* register ceked */
    public function CheckRegister(mixed $register): RedirectResponse|bool
    {
        if (!$register || !$register->verify_email) {
            abort(redirect()->route('register.message')->with(['message_token' => 'Email tidak terverifikasi silahkan register ulang']));
        }
        /* ambil dat terbaru untuk pengecekan bahwa status sudah fix */
        $register_cek = Register::where('pre_register_id', $register->id)->orderBy('updated_at', 'DESC')->first();

        if (isset ($register_cek)) {
            if ($register_cek->status == 'MENUNGGU' || $register_cek->status == 'DISETUJUI') {
                abort(redirect()->route('register.message')->with(['message_waiting' => 'Data sedang di proses upt yang dipilih atau yang terdaftar sebelumnya']));
            }
        }

        return true;
    }

    /* register saved */
    // public function RegisterStore(RegisterRequestStore $request, string $id): JsonResponse
    // {


    //     $register = PreRegister::find($id);
    //     $this->CheckRegister($register);
    //     $dokumen = DokumenPendukung::where('pre_register_id', $id)->pluck('jenis_dokumen');
    //     // dd($dokumen);
    //     if ($register->pemohon === 'perusahaan') {
    //         /* perusahaan register cek */

    //     } else {
    //         /* perorangan register cek */
    //         if ($dokumen->contains('KTP') || $dokumen->contains('PASSPORT')) {
    //             $this->SaveRegister($request, $id);
    //             return response()->json(['status' => true, 'message' => 'Register Berhasil Dilakukan'], 200);
    //         }
    //         return response()->json(['status' => false, 'message' => 'silahkan lengkapi dokumen KTP, NPWP, dan PASSPORT'], 422);
    //     }

    // }
    /* store peroeangan */
    public function StoreRegisterPerorangan(RegisterRequesPerorangantStore $request, string $id)
    {
        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        $dokumen = DokumenPendukung::where('pre_register_id', $id)->pluck('jenis_dokumen');
        if ($dokumen->contains('KTP') || $dokumen->contains('PASSPORT')) {
            $data = $request->all();
            unset($data['upt'], $data['nomor_fax'], $data['negara'], $data['provinsi'], $data['kota'], $data['pemohon']);
            $data = collect($data);
            $data = $data->merge([
                'fax' => $request->nomor_fax,
                'negara_id' => 99,
                'provinsi_id' => $request->provinsi,
                'nama_perusahaan' => $request->pemohon,
                'pre_register_id' => $id,
                'kota' => $request->kota
            ]);
            $this->SaveRegisterPerusahaanIndukPerorangan($request, $id, $data);
            return response()->json(['status' => true, 'message' => 'Register Perorangan Berhasil Dilakukan'], 200);
        }
        return response()->json(['status' => false, 'message' => 'silahkan lengkapi dokumen KTP/PASSPORT'], 422);
    }
    /* store perusahaan induk */
    public function StoreRegisterPerusahaanInduk(RegisterRequestPerusahaanIndukStore $request, string $id)
    {
        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        $dokumen = DokumenPendukung::where('pre_register_id', $id)->pluck('jenis_dokumen');
        if ($dokumen->contains('NPWP') && $dokumen->contains('NIB')) {
            $data = $request->all();
            unset($data['upt'], $data['nomor_fax'], $data['negara'], $data['provinsi'], $data['kota'], $data['pemohon']);
            $data = collect($data);
            $data = $data->merge([
                'fax' => $request->nomor_fax,
                'negara_id' => 99,
                'nama_alias_perusahaan' => $request->nama_alias_perusahaan,
                'provinsi_id' => $request->provinsi,
                'nama_perusahaan' => $request->pemohon,
                'pre_register_id' => $id,
                'kota' => $request->kota
            ]);

            $this->SaveRegisterPerusahaanIndukPerorangan($request, $id, $data);
            return response()->json(['status' => true, 'message' => 'Register Perusahaan induk Berhasil Dilakukan'], 200);
        }
        return response()->json(['status' => false, 'message' => 'silahkan lengkapi dokumen  NPWP, NIB'], 422);
    }
    public function StoreRegisterPerusahaanCabang(RegisterRequestPerusahaanCabangStore $request, string $id)
    {
        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        $dokumen = DokumenPendukung::where('pre_register_id', $id)->pluck('jenis_dokumen');
        $induk = PjBaratin::select('nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'id')->find($request->id_induk);
        // dd($induk);
        if ($dokumen->contains('NITKU')) {
            $data = $request->all();
            unset($data['upt'], $data['nomor_fax'], $data['negara'], $data['provinsi'], $data['kota'], $data['pemohon']);
            $data = collect($data);
            $data = $data->merge([
                'fax' => $request->nomor_fax,
                'jenis_identitas' => $induk->jenis_identitas,
                'nomor_identitas' => $induk->nomor_identitas,
                'negara_id' => 99,
                'nama_alias_perusahaan' => $request->nama_alias_perusahaan,
                'provinsi_id' => $request->provinsi,
                'nama_perusahaan' => $request->pemohon,
                'pre_register_id' => $id,
                'kota' => $request->kota
            ]);

            $this->SaveRegisterCabang($request, $id, $data);
            return response()->json(['status' => true, 'message' => 'Register Perusahaan cabang Berhasil Dilakukan'], 200);
        }
        return response()->json(['status' => false, 'message' => 'silahkan lengkapi dokumen  NITKU'], 422);
    }
    /* for saved register */
    public function SaveRegisterPerusahaanIndukPerorangan(Request $request, string $id, $data): void
    {
        DB::transaction(
            function () use ($data, $id, $request) {
                $baratin = PjBaratin::create($data->all());
                PreRegister::find($id)->update(['nama' => $baratin->nama_perusahaan]);
                $register_upt_user = Register::where('pre_register_id', $id)->pluck('master_upt_id')->toArray();
                foreach ($request->upt as $index => $upt) {
                    if (in_array($upt, $register_upt_user)) {
                        $register_upt_user_select = Register::where('pre_register_id', $id)->where('master_upt_id', $upt)->first();
                        if (!$register_upt_user_select->status || $register_upt_user_select->status === 'DITOLAK') {
                            Register::find($register_upt_user_select->id)->update(['pj_barantin_id' => $baratin->id, 'status' => 'MENUNGGU']);
                        }
                    } else {
                        Register::create(['master_upt_id' => $upt, 'pj_barantin_id' => $baratin->id, 'status' => 'MENUNGGU', 'pre_register_id' => $id]);
                    }

                }
                DokumenPendukung::where('pre_register_id', $id)->update(['baratin_id' => $baratin->id, 'pre_register_id' => null]);
            }
        );
        return;
    }

    public function SaveRegisterCabang(Request $request, $id, $data): void
    {
        DB::transaction(
            function () use ($data, $id, $request) {
                $baratin_cabang = BarantinCabang::create($data->all());
                PreRegister::find($id)->update(['nama' => $baratin_cabang->nama_perusahaan]);
                $register_upt_user = Register::where('pre_register_id', $id)->pluck('master_upt_id')->toArray();

                foreach ($request->upt as $index => $upt) {
                    if (in_array($upt, $register_upt_user)) {
                        $register_upt_user_select = Register::where('pre_register_id', $id)->where('master_upt_id', $upt)->first();
                        if (!$register_upt_user_select->status || $register_upt_user_select->status === 'DITOLAK') {
                            Register::find($register_upt_user_select->id)->update(['barantin_cabang_id' => $baratin_cabang->id, 'status' => 'MENUNGGU']);
                        }
                    } else {
                        Register::create(['master_upt_id' => $upt, 'barantin_cabang_id' => $baratin_cabang->id, 'status' => 'MENUNGGU', 'pre_register_id' => $id]);
                    }

                }
                DokumenPendukung::where('pre_register_id', $id)->update(['barantin_cabang_id' => $baratin_cabang->id, 'pre_register_id' => null]);
            }
        );
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
