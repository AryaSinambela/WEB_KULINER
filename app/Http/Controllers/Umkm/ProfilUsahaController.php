<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Models\Destinasi;
use App\Models\FotoDestinasi;
use App\Models\KategoriMakanan;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilUsahaController extends Controller
{
    public function edit()
    {
        $destinasi = Destinasi::with('fotos')->where('pemilik_id', Auth::id())->first();
        $wilayahs = Wilayah::orderBy('kabupaten')->get();
        $kategoris = KategoriMakanan::all();

        return view('umkm.usaha_edit', compact('destinasi', 'wilayahs', 'kategoris'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'wilayah_id'  => 'required|exists:wilayah,id',
            'kategori_id' => 'required|exists:kategori_makanan,id',
            'deskripsi'   => 'nullable|string',
            'alamat'      => 'required|string|max:255',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'harga_min'   => 'nullable|integer|min:0',
            'harga_max'   => 'nullable|integer|min:0',
            'jam_buka'    => 'nullable',
            'jam_tutup'   => 'nullable',
            'foto_utama'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $destinasi = Destinasi::updateOrCreate(
            ['pemilik_id' => Auth::id()],
            [
                'wilayah_id'        => $validated['wilayah_id'],
                'kategori_id'       => $validated['kategori_id'],
                'nama'              => $validated['nama'],
                'deskripsi'         => $validated['deskripsi'] ?? null,
                'alamat'            => $validated['alamat'],
                'latitude'          => $validated['latitude'],
                'longitude'         => $validated['longitude'],
                'harga_min'         => $validated['harga_min'] ?? null,
                'harga_max'         => $validated['harga_max'] ?? null,
                'jam_buka'          => $validated['jam_buka'] ?? null,
                'jam_tutup'         => $validated['jam_tutup'] ?? null,
                'status_verifikasi' => 'menunggu',
            ]
        );

        if ($request->hasFile('foto_utama')) {
            FotoDestinasi::where('destinasi_id', $destinasi->id)->update(['is_utama' => false]);
            $file = $request->file('foto_utama');
            $filename = \Illuminate\Support\Str::slug($destinasi->nama) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/toko'), $filename);
            FotoDestinasi::create([
                'destinasi_id' => $destinasi->id,
                'path_foto'    => 'images/toko/' . $filename,
                'is_utama'     => true,
            ]);
        }

        return redirect()->route('umkm.dashboard')
            ->with('success', 'Data destinasi usaha berhasil disimpan dan dikirim untuk verifikasi.');
    }
}