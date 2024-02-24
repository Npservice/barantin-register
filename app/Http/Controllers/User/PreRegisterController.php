<?php

namespace App\Http\Controllers\User;

use App\Models\MailToken;
use App\Models\PreRegister;
use Illuminate\Http\Request;
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
    public function index()
    {
        return view('auth.register');
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
    public function store(PreRegisterRequestStore $request): View
    {
        /* create register */
        $register = PreRegister::cretae($request);
        /* create token */
        $generate = MailToken::create(['pre_register_id' => $register->id]);

        Mail::to($register->email)->send(new MailSendTokenPreRegister($register->id, $generate->token));
        return view('auth.verify');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
}
