<?php

namespace App\Http\Controllers\Register;


use App\Models\Register;
use App\Models\MailToken;
use App\Models\PjBaratin;
use App\Models\PreRegister;
use App\Models\PjBaratanKpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use App\Mail\MailSendTokenPreRegister;
use App\Http\Requests\PreRegisterRequestStore;
use App\Http\Requests\RegisterUlangRequestStore;

class PreRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('ajax')->only(['StatusRegister', 'RegisterForm']);
    }
    public function index(): View
    {
        return view('register.register');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('register.register_ulang');
    }

    /**
     * new register create
     */
    public function NewRegister(PreRegisterRequestStore $request): View
    {

        $pemohon = $request->pemohon;
        $jenis_perusahaan = $request->jenis_permohonan;
        /* cek email in database */
        $preregister = PreRegister::with('register:id,master_upt_id,pre_register_id')->where('email', $request->email)->first();

        // seleksi dulu jenis input
        if ($preregister) {
            return $this->RegisterCheck($preregister, $request);
        }


        /* create preregister */
        $register = PreRegister::create($request->merge(['pj_baratin_id' => $request->perusahaan_induk ?? null])->except('perusahaan_induk'));
        /* create register */
        foreach ($request->upt as $key => $value) {
            Register::create(['master_upt_id' => $value, 'pre_register_id' => $register->id]);
        }
        /* create token */
        $generate = MailToken::create(['pre_register_id' => $register->id]);
        /* generate new token */
        Mail::to($register->email)->send(new MailSendTokenPreRegister($register->id, $generate->token));
        return view('register.verify', compact('generate'));

    }

    public function RegisterCheck(PreRegister $preregister, Request $request): View
    {

        PreRegister::find($preregister->id)->update($request->merge(['verify_email' => null, 'pj_baratin_id' => $request->perusahaan_induk ?? null])->all());


        foreach ($request->upt as $key => $upt) {
            /* cek keberadaan upt */
            $found = false; // Tandai apakah upt sudah ditemukan

            foreach ($preregister->register as $in => $prereg) {
                // Perbaikan untuk mengakses relasi register
                $register = Register::find($prereg->id);
                // cek upt_id di register sama dengan upt yang dipilih
                if ($register && $register->master_upt_id == $upt) {
                    // Jika upt sudah terdaftar di register, update status dan pj_baratin_id
                    $register->update(['status' => null, 'pj_baratin_id' => null, 'barantin_cabang_id' => null]);
                    $found = true; // tandai upt sudah ditemukan
                    break; // keluar dari perulangan kedua
                }
            }


            if (!$found) {
                Register::create(['master_upt_id' => $upt, 'pre_register_id' => $preregister->id]);
            }
        }
        /* clear all token */
        MailToken::where('pre_register_id', $preregister->id)->delete();
        /* generaet new token */
        $generate = MailToken::create(['pre_register_id' => $preregister->id]);
        return view('register.verify', compact('generate'));

    }

    /* register ulang */
    public function RegisterUlang(RegisterUlangRequestStore $request): View
    {
        $baratin = PjBaratin::where('kode_perusahaan', $request->username)->where('email', $request->email)->first();
        $pre_register = null;
        $generate = null;

        if ($baratin) {
            $pre_register = PreRegister::create(['nama' => $baratin->nama_perusahaan, 'email' => $baratin->email, 'status' => $baratin->status, 'pemohon' => $request->pemohon]);
            $generate = MailToken::create(['pre_register_id' => $pre_register->id]);
        } else {
            DB::transaction(function () use ($request, &$pre_register, &$generate) {
                $baratan = PjBaratanKpp::where('kode_perusahaan', $request->username)->first();
                $pre_register = PreRegister::create(['nama' => $baratan->nama_perusahaan, 'email' => $baratan->email, 'pemohon' => $request->pemohon]);
                Register::create(['master_upt_id' => $baratan->upt_id, 'pre_register_id' => $pre_register->id]);
                $generate = MailToken::create(['pre_register_id' => $pre_register->id]);
            });
        }


        Mail::to($request->email)->send(new MailSendTokenPreRegister($pre_register->id, $generate->token));
        return view('register.verify', compact('generate'));
    }

    /**
     * verify token
     */
    public function TokenVerify(string $id, string $token): RedirectResponse
    {
        /* check token and user */
        $mail_token = MailToken::where('pre_register_id', $id)->first();
        if (!$mail_token || $mail_token->token != $token || $mail_token->expire_at_token <= now()) {
            return redirect()->route('register.message')->with(['message_token' => 'Token habis silahkan register ulang']);
        }
        DB::transaction(function () use ($id) {
            $mail_token = MailToken::where('pre_register_id', $id)->delete();
            PreRegister::find($id)->update(['verify_email' => now()]);
        });

        return redirect()->route('register.formulir.index', $id);
    }

    /**
     regenerate token function
     */
    public function Regenerate(Request $request): View
    {
        $data = $request->validate([
            'token' => 'required|exists:mail_tokens,token',
            'user_id' => 'required|exists:pre_registers,id'
        ]);

        $user = null;
        $generate = null;

        DB::transaction(function () use (&$user, &$generate, $request) {
            $user = PreRegister::find($request->user_id);
            MailToken::where('pre_register_id', $user->id)->delete();
            $generate = MailToken::create(['pre_register_id' => $user->id]);
        });

        Mail::to($user->email)->send(new MailSendTokenPreRegister($user->id, $generate->token));

        return view('register.verify', compact('generate'));
    }



}
