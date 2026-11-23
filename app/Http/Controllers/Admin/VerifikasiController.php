<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use App\Models\Notifikasi;
use App\Models\PengajuanOtentik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikasiController extends Controller
{
    public function index()
    {
        $antreanDestinasi = Destinasi::with(['pemilik', 'wilayah', 'kategori'])
            ->where('status_verifikasi', 'menunggu')
            ->latest()
            ->get();

        $antreanLabel = PengajuanOtentik::with(['destinasi.wilayah', 'diajukanOleh'])
            ->where('status', 'menunggu')
            ->latest('diajukan_pada')
            ->get();

        $riwayatDestinasi = Destinasi::with(['wilayah', 'kategori'])
            ->whereIn('status_verifikasi', ['disetujui', 'ditolak'])
            ->latest('diverifikasi_pada')
            ->take(10)
            ->get();

        return view('admin.verifikasi_index', compact('antreanDestinasi', 'antreanLabel', 'riwayatDestinasi'));
    }

    public function show(Destinasi $destinasi)
    {
        $destinasi->load(['pemilik', 'wilayah', 'kategori', 'fotos', 'menus', 'ceritaBudaya', 'pengajuanOtentik']);
        return view('admin.verifikasi_show', compact('destinasi'));
    }

    // 1. Verifikasi Data Destinasi: SETUJUI
    public function setujui(Destinasi $destinasi)
    {
        $destinasi->update([
            'status_verifikasi'  => 'disetujui',
            'diverifikasi_oleh'  => Auth::id(),
            'diverifikasi_pada'  => now(),
            'catatan_verifikasi' => null,
        ]);

        Notifikasi::create([
            'user_id' => $destinasi->pemilik_id,
            'tipe'    => 'verifikasi_destinasi',
            'judul'   => 'Destinasi Kuliner Disetujui!',
            'pesan'   => "Usaha {$destinasi->nama} telah lolos verifikasi dan tampil pada Peta Kuliner Toba.",
        ]);

        return back()->with('success', "Destinasi {$destinasi->nama} berhasil disetujui dan dipublikasikan.");
    }

    // 2. Verifikasi Data Destinasi: TOLAK + Catatan (Modal A3)
    public function tolak(Request $request, Destinasi $destinasi)
    {
        $request->validate(['catatan_verifikasi' => 'required|string|min:5']);

        $destinasi->update([
            'status_verifikasi'  => 'ditolak',
            'diverifikasi_oleh'  => Auth::id(),
            'diverifikasi_pada'  => now(),
            'catatan_verifikasi' => $request->catatan_verifikasi,
        ]);

        Notifikasi::create([
            'user_id' => $destinasi->pemilik_id,
            'tipe'    => 'verifikasi_destinasi',
            'judul'   => 'Pengajuan Destinasi Perlu Perbaikan',
            'pesan'   => "Catatan Kurator: {$request->catatan_verifikasi}",
        ]);

        return back()->with('success', "Pengajuan destinasi {$destinasi->nama} ditolak beserta catatan perbaikan.");
    }

    public function revisi(Request $request, Destinasi $destinasi)
    {
        return $this->tolak($request, $destinasi);
    }

    // 3. Verifikasi Label Otentik Batak (Terpisah dari Verifikasi Destinasi)
    public function label(Request $request, Destinasi $destinasi)
    {
        $validated = $request->validate([
            'aksi'    => 'required|in:disetujui,ditolak,cabut',
            'catatan' => 'nullable|string',
        ]);

        $pengajuan = PengajuanOtentik::where('destinasi_id', $destinasi->id)
            ->where('status', 'menunggu')
            ->latest('diajukan_pada')
            ->first();

        if ($validated['aksi'] === 'disetujui') {
            $destinasi->update(['is_otentik_batak' => true]);
            if ($pengajuan) {
                $pengajuan->update([
                    'status'        => 'disetujui',
                    'ditinjau_oleh' => Auth::id(),
                    'ditinjau_pada' => now(),
                    'catatan'       => $validated['catatan'] ?? 'Memenuhi standar kurasi Kuliner Otentik Batak Toba.',
                ]);
            }
            Notifikasi::create([
                'user_id' => $destinasi->pemilik_id,
                'tipe'    => 'label_otentik',
                'judul'   => 'Selamat! Label Otentik Batak Diterbitkan',
                'pesan'   => "Destinasi {$destinasi->nama} resmi menyandang badge Kuliner Otentik Batak.",
            ]);
            return back()->with('success', "Label Otentik Batak resmi diterbitkan untuk {$destinasi->nama}.");
        }

        // Jika ditolak atau dicabut
        $destinasi->update(['is_otentik_batak' => false]);
        if ($pengajuan) {
            $pengajuan->update([
                'status'        => 'ditolak',
                'ditinjau_oleh' => Auth::id(),
                'ditinjau_pada' => now(),
                'catatan'       => $validated['catatan'] ?? 'Belum memenuhi kriteria keotentikan resep/rempah.',
            ]);
        }

        Notifikasi::create([
            'user_id' => $destinasi->pemilik_id,
            'tipe'    => 'label_otentik',
            'judul'   => 'Status Pengajuan Label Otentik Batak',
            'pesan'   => $validated['catatan'] ?? 'Pengajuan label belum disetujui. Silakan periksa catatan kurator.',
        ]);

        return back()->with('success', 'Status Label Otentik Batak berhasil diperbarui.');
    }
}