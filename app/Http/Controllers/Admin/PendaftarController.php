<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
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
use Illuminate\Support\Facades\Mail;
use App\Mail\MailSendUsernamePassword;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Builder;

class PendaftarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $uptPusatId;
    public function __construct()
    {
        $this->middleware('ajax')->except('index');
        $this->uptPusatId = env('UPT_PUSAT_ID', 1000);
    }
    public function index(): View|JsonResponse
    {
        return view('admin.pendaftar.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id): View
    {
        $data = PjBaratin::find($id) ?? BarantinCabang::with(['baratininduk:nama_perusahaan,id'])->find($id);
        $register = Register::find($request->register_id);
        $preregister = PreRegister::find($data->pre_register_id);
        $upt = BarantinApiHelper::getMasterUptByID($register->master_upt_id);

        $dataMaster = [
            'upt' => $upt['nama_satpel'] . ' - ' . $upt['nama'],
            'provinsi' => BarantinApiHelper::getMasterProvinsiByID($data->provinsi_id)['nama'],
            'kota' => BarantinApiHelper::getMasterKotaByIDProvinsiID($data->kota, $data->provinsi_id)['nama'],
            'negara' => BarantinApiHelper::getMasterNegaraByID($data->negara_id)['nama'],
        ];

        if ($preregister->pemohon === 'perorangan') {
            return view('admin.pendaftar.show.perorangan', compact('data', 'register', 'dataMaster'));
        } else {
            if ($preregister->jenis_perusahaan === 'induk' || !$preregister->jenis_perusahaan) {
                return view('admin.pendaftar.show.induk', compact('data', 'register', 'dataMaster'));
            }
            return view('admin.pendaftar.show.cabang', compact('data', 'register', 'dataMaster'));
        }
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


    public function datatable(string $pemohon): JsonResponse
    {
        $model = $this->datatableQueryCheckUser($pemohon);

        $datatable = DataTables::eloquent($model)->addIndexColumn()
            ->filterColumn('updated_at', function ($query, $keyword) {
                $range = explode(' - ', $keyword);
                if (count($range) === 2) {
                    $startDate = Carbon::parse($range[0])->startOfDay();
                    $endDate = Carbon::parse($range[1])->endOfDay();
                    $query->whereBetween('registers.updated_at', [$startDate, $endDate]);
                }

            });
        $action = $pemohon == 'cabang' ? 'admin.pendaftar.action.cabang' : 'admin.pendaftar.action.induk';
        $barantinKategori = $pemohon == 'cabang' ? 'baratincabang' : 'baratin';
        return $this->columnDaerahRender($datatable, $action, $barantinKategori);

    }

    /**
     * Menambahkan kolom-kolom daerah dan aksi ke DataTable.
     *
     * Metode ini menambahkan kolom UPT, negara, provinsi, dan kota berdasarkan ID yang terkait
     * dari model yang diberikan. Kolom aksi juga ditambahkan berdasarkan parameter yang diterima.
     *
     * @param mixed $datatable DataTable yang sedang dibangun.
     * @param string $action Nama view untuk kolom aksi.
     * @return mixed DataTable yang telah dimodifikasi dengan kolom tambahan.
     */
    private function columnDaerahRender($datatable, string $action, string $barantinKategori)
    {
        return $datatable->addColumn('upt', function ($row) {
            $upt = BarantinApiHelper::getMasterUptByID($row->master_upt_id);
            return $upt['nama_satpel'] . ' - ' . $upt['nama'];
        })
            ->addColumn('negara', function ($row) {
                $negara = BarantinApiHelper::getMasterNegaraByID($row->baratin->negara_id ?? $row->baratincabang->negara_id);
                return $negara['nama'];
            })
            ->filterColumn('negara', function ($query, $keyword) use ($barantinKategori) {
                $negara = collect(BarantinApiHelper::getDataMasterNegara()->original);
                $idNegara = JsonFilterHelper::searchDataByKeyword($negara, $keyword, 'nama')->pluck('id');
                $query->whereHas($barantinKategori, fn($query) => $query->whereIn('negara_id', $idNegara));
            })
            ->addColumn('provinsi', function ($row) {
                $provinsi = BarantinApiHelper::getMasterProvinsiByID($row->baratin->provinsi_id ?? $row->baratincabang->provinsi_id);
                return $provinsi['nama'];
            })
            ->filterColumn('provinsi', function ($query, $keyword) use ($barantinKategori) {
                $provinsi = collect(BarantinApiHelper::getDataMasterProvinsi()->original);
                $idProvinsi = JsonFilterHelper::searchDataByKeyword($provinsi, $keyword, 'nama')->pluck('id');
                $query->whereHas($barantinKategori, fn($query) => $query->whereIn('provinsi_id', $idProvinsi));
            })
            ->addColumn('kota', function ($row) {
                $kota = BarantinApiHelper::getMasterKotaByIDProvinsiID($row->baratin->kota ?? $row->baratincabang->kota, $row->baratin->provinsi_id ?? $row->baratincabang->provinsi_id);
                return $kota['nama'];
            })
            ->filterColumn('kota', function ($query, $keyword) use ($barantinKategori) {
                $kota = collect(BarantinApiHelper::getDataMasterKota()->original);
                $idKota = JsonFilterHelper::searchDataByKeyword($kota, $keyword, 'nama')->pluck('id');
                $query->whereHas($barantinKategori, fn($query) => $query->whereIn('kota', $idKota));

            })
            ->addColumn('action', $action)->make(true);
    }

    private function datatableQueryCheckUser(string $pemohon)
    {
        $uptId = auth()->guard('admin')->user()->upt_id;

        $model = $pemohon === 'cabang' ? $this->queryRegisterCabang() : $this->queryRegisterPeoranganAndInduk();

        $model = $model->whereHas('preregister', function ($query) use ($pemohon) {
            $query->where('pemohon', $pemohon == 'cabang' || $pemohon == 'perusahaan' ? 'perusahaan' : 'perorangan');
        });

        if ($uptId != $this->uptPusatId) {
            $model = $model->where('master_upt_id', $uptId);
        }

        return $model;
    }
    public function QueryRegisterCabang(): Builder
    {
        return Register::with([
            'baratincabang.baratininduk:nama_perusahaan,id',
            'baratincabang' => function ($query) {
                $query->select('id', 'email', 'nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'alamat', 'kota', 'provinsi_id', 'negara_id', 'telepon', 'fax', 'status_import', 'user_id', 'nitku', 'pj_baratin_id');
            }
        ])->select('registers.id', 'master_upt_id', 'barantin_cabang_id', 'status', 'keterangan', 'registers.updated_at', 'blockir', 'registers.pre_register_id')->whereNotNull('barantin_cabang_id')->where('registers.status', 'DISETUJUI');


    }
    public function QueryRegisterPeoranganAndInduk(): Builder
    {
        return Register::with([

            'baratin' => function ($query) {
                $query->select('id', 'email', 'nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'alamat', 'kota', 'provinsi_id', 'negara_id', 'telepon', 'fax', 'status_import', 'user_id');
            }
        ])->select('registers.id', 'master_upt_id', 'pj_barantin_id', 'status', 'keterangan', 'registers.updated_at', 'blockir', 'registers.pre_register_id')->whereNotNull('pj_barantin_id')->where('registers.status', 'DISETUJUI');


    }

    // open blokir
    public function BlockAccessPendaftar(string $id): JsonResponse
    {
        $res = null;

        DB::transaction(function () use ($id, &$res) {
            $register = Register::find($id);
            $res = $register->update(['blockir' => 1]);
            $cek = Register::where('blockir', 0)->where(function ($query) use ($register) {
                $query->where('pj_barantin_id', $register->pj_barantin_id)->orWhere('barantin_cabang_id', $register->barantin_cabang_id);
            })->exists();
            if ($cek) {
                $res = $register->baratin ? $register->baratin->user()->update(['status_user' => 0]) : $register->baratincabang->user()->update(['status_user' => 0]);
            }

        });
        if ($res) {
            return AjaxResponse::SuccessResponse('blokir berhasil di aktifkan', 'pendaftar-datatable');
        }
        return AjaxResponse::ErrorResponse('blokir gagal di aktifkan', 400);
    }

    // close blokir
    public function OpenkAccessPendaftar(string $id): JsonResponse
    {
        $res = null;
        DB::transaction(function () use ($id, &$res) {
            $register = Register::find($id);
            $register->update(['blockir' => 0]);
            $res = $register->baratin ? $register->baratin->user()->update(['status_user' => 1]) : $register->baratincabang->user()->update(['status_user' => 1]);

        });

        if ($res) {
            return AjaxResponse::SuccessResponse('blokir berhasil di nonaktifkan', 'pendaftar-datatable');
        }
        return AjaxResponse::ErrorResponse('blokir gagal di nonaktifkan', 400);
    }

    public function datatablePendukung(string $id): JsonResponse
    {
        $model = DokumenPendukung::where('baratin_id', $id)->orWhere('barantin_cabang_id', $id);

        return DataTables::eloquent($model)
            ->addIndexColumn()
            ->editColumn('file', 'register.form.partial.file_pendukung_datatable')
            ->rawColumns(['action', 'file'])
            ->toJson();
    }

    public function CreateUser(string $id): JsonResponse
    {
        $register = Register::find($id);
        $pre_register = $register->preregister;
        $barantin = $register->baratin ?? $register->baratincabang;


        // Tentukan role berdasarkan pemohon dan jenis perusahaan
        $role = 'perorangan'; // nilai default
        // dd($pre_register->pemohon);
        if ($pre_register->pemohon === 'perusahaan') {
            $role = ($pre_register->jenis_perusahaan === 'induk') ? 'induk' : 'cabang';
        }

        $user_collect = collect([
            'nama' => $barantin->nama_perusahaan,
            'email' => $barantin->email,
            'username' => $this->generateRandomString(5, $pre_register->pemohon, $pre_register->jenis_perusahaan),
            'role' => $role,
            'status_user' => 1,
            'password' => $this->generateRandomPassword(),
        ]);


        $user = User::create($user_collect->all());
        $barantin->update(['user_id' => $user->id]);
        $this->SendUsernamePasswordEmail($user->id);

        return response()->json(['table' => 'pendaftar-datatable', 'nama' => $user->nama]);
    }

    public function SendUsernamePasswordEmail(string $idOrEmail): bool|JsonResponse
    {
        $user = User::where('email', $idOrEmail)->orWhere('id', $idOrEmail)->first();
        $password = $this->generateRandomPassword(8);
        $user->update(['password' => $password]);
        Mail::to($user->email)->send(new MailSendUsernamePassword($user->username, $password));

        if (request()->input('reset')) {
            return response()->json(['table' => 'pendaftar-datatable', 'nama' => $user->nama]);
        }
        return true;

    }
    /* genareate username */
    public function generateRandomString(int $length, string $pemohon, string $jenis_perusahaan = null): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random = [0, 1, 2, 3];
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        if ($pemohon === 'perusahaan') {
            if ($jenis_perusahaan === 'induk') {
                return 'C' . $randomString;
            } else {
                return 'C' . $randomString . implode('', array_rand($random, 2));
            }
        } else {
            return 'P' . $randomString;
        }
    }

    /* generate password */
    public function generateRandomPassword(int $length = 8): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $randomPassword = '';

        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomPassword;
    }
}
