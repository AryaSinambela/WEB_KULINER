<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Models\CeritaBudaya;
use App\Models\Destinasi;
use App\Models\PengajuanOtentik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{
    public function index()
    {
        $destinasi = Destinasi::with(['pengajuanOtentik' => fn($q) => $q->latest('diajukan_pada'), 'ceritaBudaya'])
            ->where('pemilik_id', Auth::id())
            ->firstOrFail();

        return view('umkm.label', compact('destinasi'));
    }

    public function store(Request $request)
    {
        $destinasi = Destinasi::where('pemilik_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'judul_cerita'  => 'required|string|max:255',
            'asal_usul'     => 'required|string',
            'makna_adat'    => 'nullable|string',
            'istilah_lokal' => 'nullable|string',
            'catatan'       => 'nullable|string',
        ]);

        CeritaBudaya::updateOrCreate(
            ['destinasi_id' => $destinasi->id],
            [
                'judul'         => $validated['judul_cerita'],
                'asal_usul'     => $validated['asal_usul'],
                'makna_adat'    => $validated['makna_adat'] ?? null,
                'istilah_lokal' => $validated['istilah_lokal'] ?? null,
            ]
        );

        PengajuanOtentik::create([
            'destinasi_id'  => $destinasi->id,
            'diajukan_oleh' => Auth::id(),
            'status'        => 'menunggu',
            'catatan'       => $validated['catatan'] ?? 'Pengajuan kurasi keotentikan resep & rempah Batak Toba.',
            'diajukan_pada' => now(),
        ]);

        return back()->with('success', 'Pengajuan Label Otentik Batak telah dikirim ke Tim Kurator.');
    }
}