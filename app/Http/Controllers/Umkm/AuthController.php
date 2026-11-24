<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use App\Models\KategoriMakanan;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function landing()
    {
        return view('umkm.landing');
    }

    public function register()
    {
        $wilayahs = Wilayah::orderBy('kabupaten')->get();
        $kategoris = KategoriMakanan::all();
        return view('umkm.register', compact('wilayahs', 'kategoris'));
    }

    public function storeRegister(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'nama_usaha'    => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'nomor_telepon' => 'required|string|max:20',
            'wilayah_id'    => 'required|exists:wilayah,id',
            'kategori_id'   => 'required|exists:kategori_makanan,id',
            'alamat'        => 'required|string|max:255',
            'password'      => 'required|string|min:8|confirmed',
        ]);

        $user = DB::transaction(function () use ($validated) {
            $u = User::create([
                'nama'          => $validated['nama'],
                'email'         => $validated['email'],
                'nomor_telepon' => $validated['nomor_telepon'],
                'role'          => 'umkm',
                'tipe_penilai'  => 'lokal',
                'password'      => Hash::make($validated['password']),
            ]);

            Destinasi::create([
                'pemilik_id'        => $u->id,
                'wilayah_id'        => $validated['wilayah_id'],
                'kategori_id'       => $validated['kategori_id'],
                'nama'              => $validated['nama_usaha'],
                'alamat'            => $validated['alamat'],
                'latitude'          => 2.3338000,
                'longitude'         => 99.0664000,
                'status_verifikasi' => 'menunggu',
            ]);

            return $u;
        });

        Auth::login($user);
        return redirect()->route('umkm.menunggu')->with('success', 'Pendaftaran mitra berhasil! Silakan lengkapi detail lokasi & menu.');
    }

    public function login()
    {
        return view('umkm.login');
    }

    public function storeLogin(Request $request)
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
                return back()->withErrors(['email' => 'Akun Mitra Anda sedang dinonaktifkan / diblokir.']);
            }
            if (Auth::user()->role !== 'umkm') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun ini bukan akun Mitra UMKM.']);
            }
            $request->session()->regenerate();
            return redirect()->route('umkm.dashboard');
        }

        return back()->withErrors(['email' => 'Email atau kata sandi mitra tidak sesuai.']);
    }
}