<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Register;
use App\Models\PjBaratin;
use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
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
        if (request()->ajax()) {
            return $this->datatable();
        }
        return view('admin.pendaftar.index');
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
    public function show(Request $request, string $id): View
    {
        $data = PjBaratin::with(['provinsi:nama,id', 'kotas:nama,id', 'negara:id,nama'])->find($id);
        $register = Register::find($request->register_id);

        return view('admin.pendaftar.show', compact('data', 'register'));
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
    public function datatable(): JsonResponse
    {
        /* get id upt bedasarkan user yang login */
        $uptId = auth()->guard('admin')->user()->upt_id;
        /* cek user apakah mempunyai id upt */
        if ($uptId) {
            $model = $this->QueryRegister()->where('master_upt_id', $uptId);
        } else {
            $model = $this->QueryRegister();
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
            ->addColumn('action', 'admin.pendaftar.action')->make(true);
    }
    public function QueryRegister(): Builder
    {
        return Register::with([
            'upt:nama,id',
            'baratin' => function ($query) {
                $query->with(['kotas:nama,id', 'negara:id,nama', 'provinsi:nama,id'])
                    ->select('id', 'email', 'nama_perusahaan', 'jenis_identitas', 'nomor_identitas', 'alamat', 'kota', 'provinsi_id', 'negara_id', 'telepon', 'fax', 'status_import', 'user_id');
            }
        ])->select('registers.id', 'master_upt_id', 'pj_barantin_id', 'status', 'keterangan', 'registers.updated_at', 'blockir')->whereNotNull('pj_barantin_id')->where('registers.status', 'DISETUJUI');


    }

    // open blokir
    public function BlockAccessPendaftar(string $id): JsonResponse
    {
        $res = null;
        DB::transaction(function () use ($id, &$res) {
            $register = Register::find($id);
            $res = $register->update(['blockir' => 1]);
            if (!Register::where('pj_barantin_id', $register->pj_barantin_id)->where('blockir', 0)->exists()) {
                $res = $register->baratin->user()->update(['status_user' => 0]);
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
            $res = $register->baratin->user()->update(['status_user' => 1]);

        });
        if ($res) {
            return AjaxResponse::SuccessResponse('blokir berhasil di nonaktifkan', 'pendaftar-datatable');
        }
        return AjaxResponse::ErrorResponse('blokir gagal di nonaktifkan', 400);
    }

    public function datatablePendukung(string $id): JsonResponse
    {
        $model = DokumenPendukung::where('baratin_id', $id);

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
        $barantin = $register->baratin;


        $user_collect = collect([
            'nama' => $barantin->nama_perusahaan,
            'email' => $barantin->email,
            'username' => $this->generateRandomString($length = 5, $pre_register->pemohon),
            'role' => $pre_register->pemohon === 'perushaan' ? 'induk' : 'perorangan',
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
        $password = $this->generateRandomPassword($length = 8);
        $user->update(['password', $password]);
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
