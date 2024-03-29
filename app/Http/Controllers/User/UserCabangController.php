<?php

namespace App\Http\Controllers\User;

use App\Models\Register;
use App\Models\PjBaratin;
use App\Models\PreRegister;
use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
use App\Models\BarantinCabang;
use App\Models\DokumenPendukung;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\UserRequestCabangStore;
use App\Http\Requests\DokumenPendukungRequestStore;

class UserCabangController extends Controller
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
        return view('user.cabang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $register = PreRegister::create([
            'pemohon' => 'perusahaan',
            'verify_email' => now(),
            'jenis_perusahaan' => 'cabang',
            'pj_baratin_id' => auth()->user()->baratin->id
        ]);
        return view('user.cabang.create', compact('register'));
    }
    public function store(UserRequestCabangStore $request): JsonResponse
    {
        $dokumen = DokumenPendukung::where('pre_register_id', $request->id_pre_register)->pluck('jenis_dokumen');
        $induk = PjBaratin::select('nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'id')->find($request->id_induk);
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
                'pre_register_id' => $request->id_pre_register,
                'pj_baratin_id' => $induk->id,
                'kota' => $request->kota
            ]);
            $this->SaveRegisterCabang($request, $request->id_pre_register, $data);
            return response()->json(['status' => true, 'message' => 'Cabang berhasil dibuat silahkan tunggu konfirmasi upt yang dipilih']);
        }
        return response()->json(['status' => false, 'message' => 'silahkan lengkapi dokumen  NITKU'], 422);
    }
    public function SaveRegisterCabang(Request $request, $id, $data): void
    {
        DB::transaction(
            function () use ($data, $id, $request) {
                $baratin_cabang = BarantinCabang::create($data->all());
                PreRegister::find($id)->update(['nama' => $baratin_cabang->nama_perusahaan, 'email' => $baratin_cabang->email]);
                foreach ($request->upt as $index => $upt) {
                    Register::create(['master_upt_id' => $upt, 'barantin_cabang_id' => $baratin_cabang->id, 'status' => 'MENUNGGU', 'pre_register_id' => $id]);
                }
                DokumenPendukung::where('pre_register_id', $id)->update(['barantin_cabang_id' => $baratin_cabang->id, 'pre_register_id' => null]);
            }
        );
        return;
    }

    public function cancel(Request $request): JsonResponse
    {
        $request->validate(['id_pre_register' => 'required|exists:pre_registers,id']);
        $res = PreRegister::destroy($request->id_pre_register);
        if ($res) {
            return AjaxResponse::SuccessResponse('Pembuatan cabang sukses dibatalkan', 'user-cabang-datatable');
        }
        return AjaxResponse::ErrorResponse('Pembuatan cabang gagal dibatalkan', 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = BarantinCabang::with(['provinsi:nama,id', 'kotas:nama,id', 'negara:id,nama', 'baratininduk:nama_perusahaan,id'])->find($id);
        return view('user.cabang.show', compact('data'));
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
        return DataTables::eloquent($model)->addIndexColumn()->addColumn('action', 'user.cabang.action')->make(true);
    }
    public function query()
    {
        return BarantinCabang::with('negara:id,nama', 'provinsi:nama,id', 'kotas:nama,id')
            ->select('barantin_cabangs.id', 'email', 'nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'alamat', 'nitku', 'kota', 'barantin_cabangs.provinsi_id', 'negara_id', 'telepon', 'fax', 'status_import', 'user_id')
            ->where('pj_baratin_id', auth()->user()->baratin->id);
    }


    // dokumen pendukung create handler
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
    public function DokumenPendukungDataTable(string $id, Request $request): JsonResponse
    {
        if ($request->response === 'create') {
            $model = DokumenPendukung::where('pre_register_id', $id);
        } else {
            $model = DokumenPendukung::where('barantin_cabang_id', $id);
        }

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

    public function DatatableUptDetail(string $id): JsonResponse
    {
        $model = Register::with('upt:id,nama')->where('barantin_cabang_id', $id);
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->toJson();
    }
}
