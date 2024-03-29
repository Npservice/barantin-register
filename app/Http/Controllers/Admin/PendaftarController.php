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
use Illuminate\Http\JsonResponse;
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
    public function __construct()
    {
        $this->middleware('ajax')->except('index');
    }
    public function index(): View|JsonResponse
    {
        // return $this->datatableCabang();
        return view('admin.pendaftar.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id): View
    {
        $data = PjBaratin::with(['provinsi:nama,id', 'kotas:nama,id', 'negara:id,nama'])->find($id) ?? BarantinCabang::with(['provinsi:nama,id', 'kotas:nama,id', 'negara:id,nama', 'baratininduk:nama_perusahaan,id'])->find($id);
        $register = Register::find($request->register_id);
        $preregister = PreRegister::find($data->pre_register_id);

        if ($preregister->pemohon === 'perorangan') {
            return view('admin.pendaftar.show.perorangan', compact('data', 'register'));
        } else {
            if ($preregister->jenis_perusahaan === 'induk') {
                return view('admin.pendaftar.show.induk', compact('data', 'register'));
            }
            return view('admin.pendaftar.show.cabang', compact('data', 'register'));
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
            ->filterColumn('updated_at', function ($query, $keyword) {
                $range = explode(' - ', $keyword);
                if (count($range) === 2) {
                    $startDate = Carbon::parse($range[0])->startOfDay();
                    $endDate = Carbon::parse($range[1])->endOfDay();
                    $query->whereBetween('registers.updated_at', [$startDate, $endDate]);
                }

            })
            ->addColumn('action', 'admin.pendaftar.action.induk')->make(true);
    }

    /* query for barantin master table */
    public function QueryRegisterPeoranganAndInduk(): Builder
    {
        return Register::with([
            'upt:nama,id',
            'baratin.kotas:nama,id',
            'baratin.negara:id,nama',
            'baratin.provinsi:id,nama',
            'baratin' => function ($query) {
                $query->select('id', 'email', 'nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'alamat', 'kota', 'provinsi_id', 'negara_id', 'telepon', 'fax', 'status_import', 'user_id');
            }
        ])->select('registers.id', 'master_upt_id', 'pj_barantin_id', 'status', 'keterangan', 'registers.updated_at', 'blockir', 'registers.pre_register_id')->whereNotNull('pj_barantin_id')->where('registers.status', 'DISETUJUI');


    }


    /* query for barantin master table */
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
            ->filterColumn('updated_at', function ($query, $keyword) {
                $range = explode(' - ', $keyword);
                if (count($range) === 2) {
                    $startDate = Carbon::parse($range[0])->startOfDay();
                    $endDate = Carbon::parse($range[1])->endOfDay();
                    $query->whereBetween('registers.updated_at', [$startDate, $endDate]);
                }

            })
            ->addColumn('action', 'admin.pendaftar.action.cabang')->make(true);
    }


    public function QueryRegisterCabang(): Builder
    {
        return Register::with([
            'upt:nama,id',
            'baratincabang.baratininduk:nama_perusahaan,id',
            'baratincabang.kotas:nama,id',
            'baratincabang.negara:id,nama',
            'baratincabang.provinsi:id,nama',
            'baratincabang' => function ($query) {
                $query->select('id', 'email', 'nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'alamat', 'kota', 'provinsi_id', 'negara_id', 'telepon', 'fax', 'status_import', 'user_id', 'nitku', 'pj_baratin_id');
            }
        ])->select('registers.id', 'master_upt_id', 'barantin_cabang_id', 'status', 'keterangan', 'registers.updated_at', 'blockir', 'registers.pre_register_id')->whereNotNull('barantin_cabang_id')->where('registers.status', 'DISETUJUI');


    }

    // open blokir
    public function BlockAccessPendaftar(string $id)//: JsonResponse
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
        // dd($barantin);

        $user_collect = collect([
            'nama' => $barantin->nama_perusahaan,
            'email' => $barantin->email,
            'username' => $this->generateRandomString($length = 5, $pre_register->pemohon),
            'role' => $pre_register->pemohon === 'perusahaan' ? ($pre_register->jenis_perusahaan === 'induk' ? 'induk' : 'cabang') : 'perorangan',
            'status_user' => 1,
            'password' => $this->generateRandomPassword(),
        ]);
        // dd($user);

        $user = User::create($user_collect->all());
        $barantin->update(['user_id' => $user->id]);
        $this->SendUsernamePasswordEmail($user->id);

        return response()->json(['table' => 'pendaftar-datatable', 'nama' => $user->nama]);
    }

    public function SendUsernamePasswordEmail(string $IdOrEmail): bool|JsonResponse
    {
        $user = User::where('email', $IdOrEmail)->orWhere('id', $IdOrEmail)->first();
        $password = $this->generateRandomPassword(8);
        $update = $user->update(['password' => $password]);
        // return response()->json($update)
        Mail::to($user->email)->send(new MailSendUsernamePassword($user->username, $password));

        if (request()->input('reset')) {
            return response()->json(['table' => 'pendaftar-datatable', 'nama' => $user->nama]);
        }
        return true;

    }
    /* genareate username */
    function generateRandomString(int $length = 5, string $pemohon)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $pemohon === 'perusahaan' ? 'C' . $randomString : 'P' . $randomString;
    }

    /* generate password */
    function generateRandomPassword($length = 8)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $randomPassword = '';

        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomPassword;
    }
}
