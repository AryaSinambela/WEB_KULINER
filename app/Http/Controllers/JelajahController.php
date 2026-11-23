<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Models\KategoriMakanan;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class JelajahController extends Controller
{
    public function index(Request $request)
    {
        if (Destinasi::count() < 10 || \App\Models\FotoDestinasi::count() < 10) {
            app(\Database\Seeders\DatabaseSeeder::class)->run();
        }

        $query = Destinasi::with(['wilayah', 'kategori', 'fotoUtama', 'fotos', 'ulasans', 'menus', 'pemilik'])
            ->where('status_verifikasi', 'disetujui');

        // Filter Kata Kunci (nama destinasi, deskripsi, atau nama menu)
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%")
                  ->orWhereHas('menus', function ($m) use ($keyword) {
                      $m->where('nama_menu', 'like', "%{$keyword}%");
                  });
            });
        }

        // Filter Kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter Wilayah (Kabupaten atau ID Kecamatan)
        if ($request->filled('kabupaten')) {
            $query->whereHas('wilayah', function ($w) use ($request) {
                $w->where('kabupaten', $request->kabupaten);
            });
        }
        if ($request->filled('wilayah_id')) {
            $query->where('wilayah_id', $request->wilayah_id);
        }

        // Filter Toggle Otentik Batak
        if ($request->boolean('otentik')) {
            $query->where('is_otentik_batak', true);
        }

        // Pengurutan
        $sort = $request->get('sort', 'rekomendasi');
        if ($sort === 'harga_asc') {
            $query->orderBy('harga_min', 'asc');
        } elseif ($sort === 'terbaru') {
            $query->latest();
        } else {
            // Prioritaskan Otentik Batak lalu terbaru
            $query->orderByDesc('is_otentik_batak')->latest();
        }

        $destinasiList = $query->paginate(8)->withQueryString();
        $kategoris = KategoriMakanan::withCount(['destinasi' => function ($q) {
            $q->where('status_verifikasi', 'disetujui');
        }])->get();
        $kabupatens = Wilayah::select('kabupaten')->distinct()->pluck('kabupaten');

        return view('jelajah.index', compact('destinasiList', 'kategoris', 'kabupatens'));
    }
}