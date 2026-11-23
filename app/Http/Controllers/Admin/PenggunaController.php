<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        $penggunas = User::orderByDesc('created_at')->get();
        return view('admin.pengguna_index', compact('penggunas'));
    }

    public function create()
    {
        return redirect()->route('admin.pengguna.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'nomor_telepon' => 'nullable|string|max:20',
            'role'          => 'required|in:admin,umkm,wisatawan',
            'tipe_penilai'  => 'required|in:wisatawan,lokal',
            'password'      => 'required|string|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return back()->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return redirect()->route('admin.pengguna.index');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama'         => 'required|string|max:255',
            'role'         => 'required|in:admin,umkm,wisatawan',
            'tipe_penilai' => 'required|in:wisatawan,lokal',
        ]);

        $user->update($validated);
        return back()->with('success', "Data pengguna {$user->nama} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        $user->delete();
        return back()->with('success', 'Akun pengguna berhasil dihapus.');
    }

    public function blokir(User $user)
    {
        return back()->with('success', "Status akses pengguna {$user->nama} telah diperbarui.");
    }
}
