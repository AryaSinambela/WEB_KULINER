<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function peran()
    {
        return view('auth.register_peran');
    }

    public function simpanPeran(Request $request)
    {
        $request->validate(['peran' => 'required|in:wisatawan,umkm']);

        if ($request->peran === 'umkm') {
            return redirect()->route('umkm.register');
        }

        session(['register_role' => 'wisatawan']);
        return redirect()->route('register.akun');
    }

    public function akun()
    {
        return view('auth.register_akun');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'nomor_telepon' => 'nullable|string|max:20',
            'tipe_penilai'  => 'required|in:wisatawan,lokal',
            'password'      => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'nama'          => $validated['nama'],
            'email'         => $validated['email'],
            'nomor_telepon' => $validated['nomor_telepon'] ?? null,
            'role'          => 'wisatawan',
            'tipe_penilai'  => $validated['tipe_penilai'],
            'password'      => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        return redirect()->route('home')->with('success', 'Pendaftaran berhasil! Selamat menjelajah kuliner Danau Toba.');
    }

    public function verifikasi()
    {
        return redirect()->route('home');
    }
}