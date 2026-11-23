<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function request()
    {
        return view('auth.forgot_password');
    }

    public function email(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Alamat email tersebut tidak ditemukan dalam sistem.']);
        }

        $token = Str::random(32);
        return redirect()->route('password.reset', ['token' => $token, 'email' => $user->email])
            ->with('info', 'Silakan masukkan kata sandi baru untuk akun Anda.');
    }

    public function form(Request $request, string $token)
    {
        $email = $request->query('email', '');
        return view('auth.reset_password', compact('token', 'email'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('login')->with('success', 'Kata sandi berhasil diatur ulang. Silakan masuk.');
    }
}
