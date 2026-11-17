<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use App\Models\MenuDestinasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index()
    {
        $destinasi = Destinasi::with('menus')->where('pemilik_id', Auth::id())->firstOrFail();
        return view('umkm.menu_index', compact('destinasi'));
    }

    public function store(Request $request)
    {
        $destinasi = Destinasi::where('pemilik_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga'     => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'path_foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('path_foto')) {
            $validated['path_foto'] = $request->file('path_foto')->store('menu', 'public');
        }

        $validated['destinasi_id'] = $destinasi->id;
        MenuDestinasi::create($validated);

        return back()->with('success', 'Menu baru berhasil ditambahkan.');
    }

    public function update(Request $request, MenuDestinasi $menu)
    {
        abort_if($menu->destinasi->pemilik_id !== Auth::id(), 403);

        $validated = $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga'     => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'path_foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('path_foto')) {
            $validated['path_foto'] = $request->file('path_foto')->store('menu', 'public');
        }

        $menu->update($validated);
        return back()->with('success', 'Data menu berhasil diperbarui.');
    }

    public function destroy(MenuDestinasi $menu)
    {
        abort_if($menu->destinasi->pemilik_id !== Auth::id(), 403);
        $menu->delete();
        return back()->with('success', 'Menu berhasil dihapus.');
    }
}