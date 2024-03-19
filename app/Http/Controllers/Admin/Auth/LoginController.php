<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }
    public function index(): View
    {
        return view('auth.admin.login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function login(Request $request): RedirectResponse
    {
        $attempt = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);




        if (Auth::guard('admin')->attempt($attempt)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard.index');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json();
    }


}
