<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use Illuminate\Support\Facades\Auth;

class PratinjauController extends Controller
{
    public function index()
    {
        $destinasi = Destinasi::with(['pemilik', 'wilayah', 'kategori', 'fotos', 'menus', 'ceritaBudaya', 'ulasans.user'])
            ->where('pemilik_id', Auth::id())
            ->first();

        if (!$destinasi) {
            return redirect()->route('umkm.usaha.edit')->with('error', 'Silakan lengkapi data usaha terlebih dahulu.');
        }

        $itineraries = collect();
        return view('destinasi.show', compact('destinasi', 'itineraries'));
    }
}
