<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Models\FotoUlasan;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UlasanController extends Controller
{
    public function create(Destinasi $destinasi)
    {
        $existing = Ulasan::where('destinasi_id', $destinasi->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return redirect()->route('ulasan.edit', $existing)
                ->with('info', 'Anda sudah memberikan ulasan untuk destinasi ini. Silakan perbarui ulasan Anda.');
        }

        return view('ulasan.create', compact('destinasi'));
    }

    public function store(Request $request, Destinasi $destinasi)
    {
        $validated = $request->validate([
            'rating_umum'        => 'required|integer|min:1|max:5',
            'rating_rasa'        => 'nullable|integer|min:1|max:5',
            'rating_harga'       => 'nullable|integer|min:1|max:5',
            'rating_keotentikan' => 'nullable|integer|min:1|max:5',
            'komentar'           => 'required|string|min:10|max:1000',
            'fotos.*'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::transaction(function () use ($request, $destinasi, $validated) {
            $user = Auth::user();
            $ulasan = Ulasan::updateOrCreate(
                ['destinasi_id' => $destinasi->id, 'user_id' => $user->id],
                [
                    'tipe_penilai'       => $user->tipe_penilai ?? 'wisatawan',
                    'rating_umum'        => $validated['rating_umum'],
                    'rating_rasa'        => $validated['rating_rasa'] ?? null,
                    'rating_harga'       => $validated['rating_harga'] ?? null,
                    'rating_keotentikan' => $validated['rating_keotentikan'] ?? null,
                    'komentar'           => $validated['komentar'],
                    'status_moderasi'    => 'tampil',
                ]
            );

            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $file) {
                    $path = $file->store('ulasan', 'public');
                    FotoUlasan::create([
                        'ulasan_id' => $ulasan->id,
                        'path_foto' => $path,
                    ]);
                }
            }
        });

        return redirect()->route('destinasi.show', $destinasi)
            ->with('success', 'Terima kasih! Ulasan dan rating Anda berhasil disimpan.');
    }

    public function edit(Ulasan $ulasan)
    {
        abort_if($ulasan->user_id !== Auth::id(), 403);
        $destinasi = $ulasan->destinasi;
        return view('ulasan.create', compact('destinasi', 'ulasan'));
    }

    public function update(Request $request, Ulasan $ulasan)
    {
        abort_if($ulasan->user_id !== Auth::id(), 403);
        return $this->store($request, $ulasan->destinasi);
    }

    public function destroy(Ulasan $ulasan)
    {
        abort_if($ulasan->user_id !== Auth::id(), 403);
        $ulasan->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}