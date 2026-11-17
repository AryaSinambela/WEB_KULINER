<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use App\Models\KategoriMakanan;
use App\Models\PengajuanOtentik;
use App\Models\Ulasan;
use App\Models\Wilayah;

class DashboardController extends Controller
{
    public function index()
    {
        $rekap = [
            'total_destinasi'    => Destinasi::where('status_verifikasi', 'disetujui')->count(),
            'destinasi_menunggu' => Destinasi::where('status_verifikasi', 'menunggu')->count(),
            'label_menunggu'     => PengajuanOtentik::where('status', 'menunggu')->count(),
            'total_otentik'      => Destinasi::where('is_otentik_batak', true)->count(),
            'rata_rating'        => round(Ulasan::where('status_moderasi', 'tampil')->avg('rating_umum') ?? 0, 2),
        ];

        $sebaranWilayah = Wilayah::withCount(['destinasi' => fn($q) => $q->where('status_verifikasi', 'disetujui')])
            ->orderByDesc('destinasi_count')
            ->get();

        $kategoriPopuler = KategoriMakanan::withCount(['destinasi' => fn($q) => $q->where('status_verifikasi', 'disetujui')])
            ->orderByDesc('destinasi_count')
            ->get();

        return view('admin.dashboard', compact('rekap', 'sebaranWilayah', 'kategoriPopuler'));
    }
}