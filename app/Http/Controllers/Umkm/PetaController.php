<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use App\Models\WisataAlam;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    public function index()
    {
        $destinasi = Destinasi::with(['wilayah', 'kategori'])
            ->where('pemilik_id', auth()->id())
            ->first();

        if (!$destinasi) {
            return redirect()->route('umkm.usaha.edit')
                ->with('error', 'Silakan lengkapi profil destinasi usaha Anda terlebih dahulu.');
        }

        // Ambil destinasi sekitar & titik wisata alam di wilayah/kaldera untuk analisis posisi strategis
        $sekitarKuliner = Destinasi::with(['wilayah', 'kategori'])
            ->where('status_verifikasi', 'disetujui')
            ->where('id', '!=', $destinasi->id)
            ->get();

        $wisataAlams = WisataAlam::with('wilayah')->get();

        return view('umkm.peta', compact('destinasi', 'sekitarKuliner', 'wisataAlams'));
    }
}
