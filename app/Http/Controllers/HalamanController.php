<?php

namespace App\Http\Controllers;

use App\Models\CeritaBudaya;
use App\Models\Destinasi;
use App\Models\MenuDestinasi;

class HalamanController extends Controller
{
    public function tentang()
    {
        $totalToko = Destinasi::where('status_verifikasi', 'disetujui')->count();
        $totalMenu = MenuDestinasi::count();
        $totalCerita = CeritaBudaya::count();

        return view('halaman.tentang', compact('totalToko', 'totalMenu', 'totalCerita'));
    }
}
