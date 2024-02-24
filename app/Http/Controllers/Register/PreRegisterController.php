<?php

namespace App\Http\Controllers\Register;


use App\Models\MailToken;
use App\Models\PreRegister;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailSendTokenPreRegister;
use App\Http\Requests\PreRegisterRequestStore;

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
        /* cek email in database */
        $register_cek = PreRegister::where('email', $request->email)->first();

        if ($register_cek) {
            $register = PreRegister::find($register_cek->id)->update($request->all());
            /* clear all token */
            MailToken::where('pre_register_id', $register_cek->id)->delete();
            /* generaet new token */
            $generate = MailToken::create(['pre_register_id' => $register_cek->id]);
            return view('register.verify', compact('generate'));
        }

        /* create register */
        $register = PreRegister::create($request->all());
        /* create token */
        $generate = MailToken::create(['pre_register_id' => $register->id]);
        /* generate new token */
        Mail::to($register->email)->send(new MailSendTokenPreRegister($register->id, $generate->token));
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
            return redirect()->route('register.failed')->with(['message_token' => 'Token Expired or Invalid. Please register again.']);
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
    /* register  form index */
    public function RegisterFormulirIndex(string $id): View|RedirectResponse
    {
        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        return view('register.form.index', compact('id'));
    }
    /* register form request by ajax */
    public function RegisterForm(string $id): View
    {
        $register = PreRegister::find($id);
        $this->CheckRegister($register);
        return view('register.form.partial.perorangan', compact('register'));
    }
    /* status register */
    public function StatusRegister(): View
    {
        return view('register.form.partial.status-register');
    }
    /* failed register */
    public function RegisterFailed(): View
    {
        $message = session('message_token');
        return view('register.failed', compact('message'));
    }
    static function CheckRegister(PreRegister $register): RedirectResponse|bool
    {
        if (!$register || !$register->verify_email) {
            return redirect()->route('register.failed')->with(['message_token' => 'Email Not Verified. Please register again.']);
        }
        return true;
    }


}
