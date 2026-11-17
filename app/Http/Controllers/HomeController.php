<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Models\KategoriMakanan;
use App\Models\Ulasan;
use App\Models\Wilayah;

class HomeController extends Controller
{
    public function index()
    {
        $kategoris = KategoriMakanan::withCount(['destinasi' => function ($q) {
            $q->where('status_verifikasi', 'disetujui');
        }])->take(6)->get();

        $wilayahs = Wilayah::select('kabupaten')->distinct()->pluck('kabupaten');

        $sorotanOtentik = Destinasi::with(['wilayah', 'kategori', 'fotoUtama', 'ulasans'])
            ->where('status_verifikasi', 'disetujui')
            ->where('is_otentik_batak', true)
            ->latest()
            ->take(4)
            ->get();

        $ulasanTerkini = Ulasan::with(['user', 'destinasi.wilayah'])
            ->where('status_moderasi', 'tampil')
            ->latest('created_at')
            ->take(2)
            ->get();

        $stats = [
            'destinasi' => Destinasi::where('status_verifikasi', 'disetujui')->count(),
            'ulasan_lokal' => Ulasan::where('tipe_penilai', 'lokal')->where('status_moderasi', 'tampil')->count(),
        ];

        return view('welcome', compact('kategoris', 'wilayahs', 'sorotanOtentik', 'ulasanTerkini', 'stats'));
    }
}