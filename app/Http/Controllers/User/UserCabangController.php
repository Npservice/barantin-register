<?php

namespace App\Http\Controllers\User;

use App\Models\Register;
use App\Models\PjBaratin;
use App\Models\PreRegister;
use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
use App\Models\BarantinCabang;
use App\Models\DokumenPendukung;
use App\Helpers\JsonFilterHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\BarantinApiHelper;
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
                'lingkup_aktifitas' => implode(',', $request->lingkup_aktivitas),
                'nama_alias_perusahaan' => $request->nama_alias_perusahaan,
                'provinsi_id' => $request->provinsi,
                'nama_perusahaan' => $request->pemohon,
                'pre_register_id' => $request->id_pre_register,
                'pj_baratin_id' => $induk->id,
                'kota' => $request->kota,
                'persetujuan_induk' => 'DISETUJUI',
            ]);
            $this->SaveRegisterCabang($request, $request->id_pre_register, $data);
            return response()->json(['status' => true, 'message' => 'Cabang berhasil dibuat silahkan tunggu konfirmasi upt yang dipilih']);
        }
        return response()->json(['status' => false, 'message' => 'silahkan lengkapi dokumen  NITKU'], 422);
    }
    public function SaveRegisterCabang(Request $request, $id, $data): bool
    {
        DB::transaction(
            function () use ($data, $id, $request) {
                $baratin_cabang = BarantinCabang::create($data->all());
                PreRegister::find($id)->update(['nama' => $baratin_cabang->nama_perusahaan, 'email' => $baratin_cabang->email]);
                foreach ($request->upt as $upt) {
                    Register::create(['master_upt_id' => $upt, 'barantin_cabang_id' => $baratin_cabang->id, 'status' => 'MENUNGGU', 'pre_register_id' => $id]);
                }
                DokumenPendukung::where('pre_register_id', $id)->update(['barantin_cabang_id' => $baratin_cabang->id, 'pre_register_id' => null]);
            }
        );
        return true;
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
        $data = BarantinCabang::with('baratininduk:nama_perusahaan,id')->find($id);
        return view('user.cabang.show', compact('data'));
    }

    public function datatable(): JsonResponse
    {
        $model = $this->query();
        return DataTables::eloquent($model)->addIndexColumn()
            ->addColumn('negara', function ($row) {
                $negara = BarantinApiHelper::getMasterNegaraByID($row->negara_id);
                return $negara['nama'];
            })
            ->filterColumn('negara', function ($query, $keyword) {
                $negara = collect(BarantinApiHelper::getDataMasterNegara()->original);
                $idNegara = JsonFilterHelper::searchDataByKeyword($negara, $keyword, 'nama')->pluck('id');
                $query->whereIn('negara_id', $idNegara);
            })
            ->addColumn('provinsi', function ($row) {
                $provinsi = BarantinApiHelper::getMasterProvinsiByID($row->provinsi_id);
                return $provinsi['nama'];
            })
            ->filterColumn('provinsi', function ($query, $keyword) {
                $provinsi = collect(BarantinApiHelper::getDataMasterProvinsi()->original);
                $idProvinsi = JsonFilterHelper::searchDataByKeyword($provinsi, $keyword, 'nama')->pluck('id');
                $query->whereIn('provinsi_id', $idProvinsi);
            })
            ->addColumn('kota', function ($row) {
                $kota = BarantinApiHelper::getMasterKotaByIDProvinsiID($row->kota, $row->provinsi_id);
                return $kota['nama'];
            })
            ->filterColumn('kota', function ($query, $keyword) {
                $kota = collect(BarantinApiHelper::getDataMasterKota()->original);
                $idKota = JsonFilterHelper::searchDataByKeyword($kota, $keyword, 'nama')->pluck('id');
                $query->whereIn('kota', $idKota);

            })
            ->addColumn('action', 'user.cabang.action')->make(true);
    }
    public function query()
    {
        return BarantinCabang::select('barantin_cabangs.id', 'email', 'nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'alamat', 'nitku', 'kota', 'barantin_cabangs.provinsi_id', 'negara_id', 'telepon', 'fax', 'status_import', 'user_id', 'persetujuan_induk')
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
        Storage::disk('public')->delete($data->file);
        $res = $data->delete();

        if ($res) {
            return AjaxResponse::SuccessResponse('dokumen pendukung berhasil dihapus', 'datatable-dokumen-pendukung');
        }
        return AjaxResponse::ErrorResponse('dokumen pendukung gagal dihapus', 200);
    }

    public function DatatableUptDetail(string $id): JsonResponse
    {
        $model = Register::where('barantin_cabang_id', $id);
        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->addColumn('upt', function ($row) {
                $upt = BarantinApiHelper::getMasterUptByID($row->master_upt_id);
                return $upt['nama_satpel'] . ' - ' . $upt['nama'];
            })
            ->filterColumn('upt', function ($query, $keyword) {
                $upt = collect(BarantinApiHelper::getDataMasterUpt()->original);
                $idUpt = JsonFilterHelper::searchDataByKeyword($upt, $keyword, 'nama_satpel', 'nama')->pluck('id');
                $query->whereIn('master_upt_id', $idUpt);
            })
            ->toJson();
    }
    public function confirmasi(Request $request, string $cabang_id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:DISETUJUI,DITOLAK',
        ]);
        $res = BarantinCabang::find($cabang_id)->update(['persetujuan_induk' => $request->status]);
        if ($res) {
            return AjaxResponse::SuccessResponse('cabang berhasil disetujui', 'user-cabang-datatable');
        }
        return AjaxResponse::ErrorResponse('cabang gagal disetujui', 200);
    }
}
