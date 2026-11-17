<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriMakanan;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate(['nama' => 'required|string|max:100|unique:kategori_makanan,nama']);
        KategoriMakanan::create($validated);
        return back()->with('success', 'Kategori makanan berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriMakanan $kategori)
    {
        $validated = $request->validate(['nama' => 'required|string|max:100']);
        $kategori->update($validated);
        return back()->with('success', 'Kategori makanan diperbarui.');
    }

    public function destroy(KategoriMakanan $kategori)
    {
        $kategori->delete();
        return back()->with('success', 'Kategori makanan dihapus.');
    }
}