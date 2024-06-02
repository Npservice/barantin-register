<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Helpers\AjaxResponse;
use App\Models\PengajuanUpdatePj;
use App\Rules\KeteranganUpdateRule;
use App\Http\Controllers\Controller;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('ajax')->except('index');
    }
    public function index()
    {
        return view('user.profile.index');
    }
    public function FormKeteranganUpdate()
    {
        return view('user.profile.form-keterangan-update');
    }
    public function RequestUpdate(Request $request)
    {
        $request->validate([
            'keterangan' => ['required', 'max:255', new KeteranganUpdateRule],
        ]);
        $res = PengajuanUpdatePj::create([
            'pj_baratin_id' => auth()->user()->baratin->id ?? null,
            'barantin_cabang_id' => auth()->user()->baratincabang->id ?? null,
            'keterangan' => $request->keterangan,
        ]);
        if ($res) {
            return AjaxResponse::SuccessResponse('Update profile berhasil diajukan. Anda akan menerima email bila persetujuan anda diterima');
        }
        return AjaxResponse::ErrorResponse('Update profile gagaldiajukan', 400);

    }
}
