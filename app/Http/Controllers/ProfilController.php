<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profil.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'tipe_penilai'  => 'required|in:wisatawan,lokal',
        ]);

        $user->update($validated);
        return back()->with('success', 'Profil & preferensi penilai berhasil diperbarui.');
    }

    public function keamanan()
    {
        return view('profil.keamanan');
    }

    public function ubahSandi(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password'      => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Kata sandi lama tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Kata sandi akun berhasil diperbarui.');
    }

    public function ulasanSaya()
    {
        $ulasans = Ulasan::with(['destinasi.wilayah', 'fotos'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('profil.ulasan', compact('ulasans'));
    }
}
