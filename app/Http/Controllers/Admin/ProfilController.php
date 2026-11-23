<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        $logVerifikasi = Destinasi::with(['wilayah', 'pemilik'])
            ->whereNotNull('diverifikasi_pada')
            ->orderByDesc('diverifikasi_pada')
            ->get();

        return view('admin.profil', compact('admin', 'logVerifikasi'));
    }

    public function exportLog()
    {
        $logs = Destinasi::with('wilayah')->whereNotNull('diverifikasi_pada')->get();
        $csv = "ID,Nama Toko,Wilayah,Status Verifikasi,Otentik Batak,Diverifikasi Pada\n";
        foreach ($logs as $l) {
            $wil = ($l->wilayah->nama_kecamatan ?? '') . ' - ' . ($l->wilayah->kabupaten ?? '');
            $ot = $l->is_otentik_batak ? 'Ya' : 'Tidak';
            $csv .= "{$l->id},\"{$l->nama}\",\"{$wil}\",{$l->status_verifikasi},{$ot},{$l->diverifikasi_pada}\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="log-kurasi-toba-kuliner.csv"',
        ]);
    }
}
