<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('notifikasi.index', compact('notifikasis'));
    }

    public function bacaSemua()
    {
        Notifikasi::where('user_id', Auth::id())->update(['dibaca' => true]);

        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }
}
