<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function menunggu()
    {
        $destinasi = Destinasi::with(['wilayah', 'kategori'])
            ->where('pemilik_id', Auth::id())
            ->first();

        return view('umkm.menunggu', compact('destinasi'));
    }

    public function index()
    {
        $destinasi = Destinasi::with(['wilayah', 'kategori', 'fotoUtama', 'menus', 'ulasans.user', 'statistik'])
            ->where('pemilik_id', Auth::id())
            ->first();

        if (!$destinasi) {
            return redirect()->route('umkm.usaha.edit')
                ->with('info', 'Silakan lengkapi profil destinasi usaha kuliner Anda terlebih dahulu.');
        }

        if ($destinasi->status_verifikasi === 'menunggu') {
            return redirect()->route('umkm.menunggu');
        }

        $totalDilihat =$destinasi->statistik->sum('profil_dilihat');
        $totalKlikRute =$destinasi->statistik->sum('klik_rute');
        $totalUlasan =$destinasi->ulasans->count();

        return view('umkm.dashboard', compact('destinasi', 'totalDilihat', 'totalKlikRute', 'totalUlasan'));
    }
}