<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Models\StatistikKunjungan;
use Illuminate\Support\Facades\Auth;

class DestinasiController extends Controller
{
    public function show(Destinasi $destinasi)
    {
        abort_if($destinasi->status_verifikasi !== 'disetujui' && (!Auth::check() || Auth::user()->role === 'wisatawan'), 404);

        // Catat statistik profil_dilihat harian (BPMN Target S5)
        $stat = StatistikKunjungan::firstOrCreate(
            ['destinasi_id' => $destinasi->id, 'tanggal' => now()->toDateString()],
            ['profil_dilihat' => 0, 'klik_rute' => 0]
        );
        $stat->increment('profil_dilihat');

        $destinasi->load([
            'pemilik',
            'wilayah',
            'kategori',
            'fotos',
            'menus',
            'ceritaBudaya',
            'ulasans.user',
            'ulasans.fotos',
        ]);

        $itineraries = Auth::check()
            ? Auth::user()->itineraries()->latest()->get()
            : collect();

        return view('destinasi.show', compact('destinasi', 'itineraries'));
    }

    public function rute(Destinasi $destinasi)
    {
        // Catat statistik klik_rute harian (BPMN Target S5)
        $stat = StatistikKunjungan::firstOrCreate(
            ['destinasi_id' => $destinasi->id, 'tanggal' => now()->toDateString()],
            ['profil_dilihat' => 0, 'klik_rute' => 0]
        );
        $stat->increment('klik_rute');

        $mapsUrl = "https://www.google.com/maps/dir/?api=1&destination={$destinasi->latitude},{$destinasi->longitude}";
        return redirect()->away($mapsUrl);
    }
}