<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function index()
    {
        $ulasans = Ulasan::with(['user', 'destinasi', 'fotos'])
            ->latest('created_at')
            ->paginate(15);

        return view('admin.ulasan_index', compact('ulasans'));
    }

    public function moderasi(Request $request, Ulasan $ulasan)
    {
        $validated = $request->validate([
            'status_moderasi' => 'required|in:tampil,ditahan,dihapus',
        ]);

        $ulasan->update(['status_moderasi' => $validated['status_moderasi']]);

        return back()->with('success', "Status moderasi ulasan diubah menjadi: {$validated['status_moderasi']}.");
    }
}