<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Models\KategoriMakanan;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function peran()
    {
        $wilayahs = Wilayah::orderBy('kabupaten')->get();
        $kategoris = KategoriMakanan::all();
        return view('auth.register_peran', compact('wilayahs', 'kategoris'));
    }

    public function simpanPeran(Request $request)
    {
        $request->validate(['peran' => 'required|in:wisatawan,lokal,umkm']);

        if ($request->peran === 'umkm') {
            return redirect()->route('umkm.register');
        }

        session([
            'register_role' => 'wisatawan',
            'register_tipe_penilai' => $request->peran,
        ]);
        return redirect()->route('register.akun', ['tipe' => $request->peran]);
    }

    public function akun(Request $request)
    {
        $defaultTipe = $request->query('tipe', session('register_tipe_penilai', 'wisatawan'));
        $wilayahs = Wilayah::orderBy('kabupaten')->get();
        $kategoris = KategoriMakanan::all();
        return view('auth.register_akun', compact('defaultTipe', 'wilayahs', 'kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'nomor_telepon' => 'nullable|string|max:20',
            'jenis_akun'    => 'nullable|in:wisatawan,lokal,umkm',
            'tipe_penilai'  => 'nullable|in:wisatawan,lokal',
            'nama_usaha'    => 'nullable|string|max:255',
            'wilayah_id'    => 'nullable|exists:wilayah,id',
            'kategori_id'   => 'nullable|exists:kategori_makanan,id',
            'alamat'        => 'nullable|string|max:255',
            'password'      => 'required|string|min:6|confirmed',
        ]);

        $jenis = $validated['jenis_akun'] ?? $validated['tipe_penilai'] ?? 'wisatawan';

        $user = DB::transaction(function () use ($validated, $jenis) {
            $isUmkm = ($jenis === 'umkm');
            $u = User::create([
                'nama'          => $validated['nama'],
                'email'         => $validated['email'],
                'nomor_telepon' => $validated['nomor_telepon'] ?? null,
                'role'          => $isUmkm ? 'umkm' : 'wisatawan',
                'tipe_penilai'  => ($jenis === 'lokal' || $isUmkm) ? 'lokal' : 'wisatawan',
                'password'      => Hash::make($validated['password']),
            ]);

            if ($isUmkm) {
                $firstWilayah = Wilayah::first();
                $firstKategori = KategoriMakanan::first();
                Destinasi::create([
                    'pemilik_id'        => $u->id,
                    'wilayah_id'        => $validated['wilayah_id'] ?? ($firstWilayah->id ?? 1),
                    'kategori_id'       => $validated['kategori_id'] ?? ($firstKategori->id ?? 1),
                    'nama'              => !empty($validated['nama_usaha']) ? $validated['nama_usaha'] : ('Kedai Kuliner ' . $validated['nama']),
                    'alamat'            => !empty($validated['alamat']) ? $validated['alamat'] : 'Kawasan Kaldera Danau Toba',
                    'latitude'          => 2.3338000,
                    'longitude'         => 99.0664000,
                    'status_verifikasi' => 'menunggu',
                ]);
            }

            return $u;
        });

        Auth::login($user);

        if ($user->role === 'umkm') {
            return redirect()->route('umkm.dashboard')->with('success', 'Pendaftaran akun & toko berhasil! Silakan kelola profil toko dan menu kuliner Anda.');
        }

        return redirect()->route('home')->with('success', 'Pendaftaran akun berhasil! Sekarang Anda dapat menyimpan rencana kunjungan dan memberi ulasan.');
    }

    public function verifikasi()
    {
        return redirect()->route('home');
    }
}