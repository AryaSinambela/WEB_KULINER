<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Models\CeritaBudaya;
use App\Models\Destinasi;
use App\Models\MenuDestinasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index()
    {
        $destinasi = Destinasi::with(['menus', 'ceritaBudaya'])->where('pemilik_id', Auth::id())->first();
        if (!$destinasi) {
            return redirect()->route('umkm.usaha.edit')->with('error', 'Silakan lengkapi profil toko terlebih dahulu sebelum mengelola menu.');
        }
        return view('umkm.menu_index', compact('destinasi'));
    }

    public function create()
    {
        return redirect()->route('umkm.menu.index');
    }

    public function edit(MenuDestinasi $menu)
    {
        return redirect()->route('umkm.menu.index');
    }

    public function store(Request $request)
    {
        $destinasi = Destinasi::where('pemilik_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'nama_menu'     => 'required|string|max:255',
            'harga'         => 'required|integer|min:0',
            'deskripsi'     => 'nullable|string',
            'path_foto'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'asal_usul'     => 'nullable|string',
            'makna_adat'    => 'nullable|string',
            'istilah_lokal' => 'nullable|string',
        ]);

        if ($request->hasFile('path_foto')) {
            $file = $request->file('path_foto');
            $filename = \Illuminate\Support\Str::slug($validated['nama_menu']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/menu'), $filename);
            $validated['path_foto'] = 'images/menu/' . $filename;
        }

        MenuDestinasi::create([
            'destinasi_id' => $destinasi->id,
            'nama_menu'    => $validated['nama_menu'],
            'harga'        => $validated['harga'],
            'deskripsi'    => $validated['deskripsi'] ?? null,
            'path_foto'    => $validated['path_foto'] ?? null,
        ]);

        if (!empty($validated['asal_usul']) || !empty($validated['makna_adat'])) {
            CeritaBudaya::create([
                'destinasi_id'  => $destinasi->id,
                'judul'         => $validated['nama_menu'] . ': Warisan Rasa ' . $destinasi->nama,
                'asal_usul'     => $validated['asal_usul'] ?? 'Resep warisan keluarga turun-temurun.',
                'makna_adat'    => $validated['makna_adat'] ?? 'Disajikan sebagai wujud penghormatan dan kekeluargaan adat Batak.',
                'istilah_lokal' => $validated['istilah_lokal'] ?? null,
            ]);
        }

        return back()->with('success', 'Menu baru beserta cerita budayanya berhasil ditambahkan.');
    }

    public function update(Request $request, MenuDestinasi $menu)
    {
        abort_if($menu->destinasi->pemilik_id !== Auth::id(), 403);

        $validated = $request->validate([
            'nama_menu'     => 'required|string|max:255',
            'harga'         => 'required|integer|min:0',
            'deskripsi'     => 'nullable|string',
            'path_foto'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cerita_id'     => 'nullable|integer',
            'asal_usul'     => 'nullable|string',
            'makna_adat'    => 'nullable|string',
            'istilah_lokal' => 'nullable|string',
        ]);

        $menuData = [
            'nama_menu' => $validated['nama_menu'],
            'harga'     => $validated['harga'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ];

        if ($request->hasFile('path_foto')) {
            $file = $request->file('path_foto');
            $filename = \Illuminate\Support\Str::slug($validated['nama_menu']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/menu'), $filename);
            $menuData['path_foto'] = 'images/menu/' . $filename;
        }

        $menu->update($menuData);

        if (!empty($validated['asal_usul']) || !empty($validated['makna_adat'])) {
            $cerita = !empty($validated['cerita_id'])
                ? CeritaBudaya::where('id', $validated['cerita_id'])->where('destinasi_id', $menu->destinasi_id)->first()
                : null;

            if ($cerita) {
                $cerita->update([
                    'judul'         => $validated['nama_menu'] . ': Warisan Rasa ' . $menu->destinasi->nama,
                    'asal_usul'     => $validated['asal_usul'] ?? $cerita->asal_usul,
                    'makna_adat'    => $validated['makna_adat'] ?? $cerita->makna_adat,
                    'istilah_lokal' => $validated['istilah_lokal'] ?? $cerita->istilah_lokal,
                ]);
            } else {
                CeritaBudaya::create([
                    'destinasi_id'  => $menu->destinasi_id,
                    'judul'         => $validated['nama_menu'] . ': Warisan Rasa ' . $menu->destinasi->nama,
                    'asal_usul'     => $validated['asal_usul'] ?? 'Resep warisan keluarga turun-temurun.',
                    'makna_adat'    => $validated['makna_adat'] ?? 'Disajikan sebagai wujud penghormatan dan kekeluargaan adat Batak.',
                    'istilah_lokal' => $validated['istilah_lokal'] ?? null,
                ]);
            }
        }

        return back()->with('success', 'Data menu & cerita budaya berhasil diperbarui.');
    }

    public function destroy(MenuDestinasi $menu)
    {
        abort_if($menu->destinasi->pemilik_id !== Auth::id(), 403);
        $menu->delete();
        return back()->with('success', 'Menu berhasil dihapus.');
    }
}