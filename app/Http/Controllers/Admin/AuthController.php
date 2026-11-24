<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (!Auth::user()->is_aktif) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['email' => 'Akun Administrator ini sedang dinonaktifkan.']);
            }
            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akses ditolak. Akun ini tidak memiliki hak Administrator/Kurator.']);
            }
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Kredensial administrator tidak valid.']);
    }
}