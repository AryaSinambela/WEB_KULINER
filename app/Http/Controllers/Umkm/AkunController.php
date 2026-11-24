<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('umkm.akun', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'password_baru' => 'nullable|string|min:8',
        ]);

        $data = [
            'nama'          => $validated['nama'],
            'nomor_telepon' => $validated['nomor_telepon'],
        ];

        if (!empty($validated['password_baru'])) {
            $data['password'] = Hash::make($validated['password_baru']);
        }

        $user->update($data);
        return back()->with('success', 'Pengaturan pada akun mitra berhasil diperbarui.');
    }
}
