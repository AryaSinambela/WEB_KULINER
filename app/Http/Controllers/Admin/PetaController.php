<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use App\Models\KategoriMakanan;
use App\Models\Wilayah;
use App\Models\WisataAlam;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    public function index(Request $request)
    {
        $wilayahs = Wilayah::withCount('destinasi')->orderBy('kabupaten')->get();
        $kategoris = KategoriMakanan::withCount('destinasi')->get();

        $destinasis = Destinasi::with(['wilayah', 'kategori', 'pemilik'])->get();
        $wisataAlams = WisataAlam::with('wilayah')->get();

        $rekapStatus = [
            'disetujui' => $destinasis->where('status_verifikasi', 'disetujui')->count(),
            'menunggu' => $destinasis->where('status_verifikasi', 'menunggu')->count(),
            'ditolak' => $destinasis->where('status_verifikasi', 'ditolak')->count(),
            'otentik' => $destinasis->where('is_otentik_batak', true)->count(),
        ];

        return view('admin.peta', compact('wilayahs', 'kategoris', 'destinasis', 'wisataAlams', 'rekapStatus'));
    }
}
