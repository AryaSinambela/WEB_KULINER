<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kecamatan' => 'required|string|max:100',
            'kabupaten'      => 'required|string|max:100',
        ]);
        Wilayah::create($validated);
        return back()->with('success', 'Data wilayah berhasil ditambahkan.');
    }

    public function update(Request $request, Wilayah $wilayah)
    {
        $validated = $request->validate([
            'nama_kecamatan' => 'required|string|max:100',
            'kabupaten'      => 'required|string|max:100',
        ]);
        $wilayah->update($validated);
        return back()->with('success', 'Data wilayah diperbarui.');
    }

    public function destroy(Wilayah $wilayah)
    {
        $wilayah->delete();
        return back()->with('success', 'Data wilayah dihapus.');
    }
}