@extends('layouts.app')

@section('title', 'Manajemen Pengguna & Mitra — Konsol Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard Admin</a>
            <h1 class="h3 fw-bold mb-0 mt-1">Manajemen Pengguna, Warga Lokal, &amp; Mitra Toko</h1>
        </div>
        <button type="button" class="btn btn-toba-primary btn-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalTambahPengguna">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna Baru
        </button>
    </div>

    <div class="card-toba p-4">
        <div class="table-responsive">
            <table class="table align-middle small">
                <thead class="table-light">
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Email &amp; Telepon</th>
                        <th>Peran Sistem</th>
                        <th>Bobot Penilai</th>
                        <th>Status Akun</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penggunas as $u)
                        <tr>
                            <td class="fw-bold">{{ $u->nama }}</td>
                            <td>
                                {{ $u->email }}
                                @if($u->nomor_telepon)
                                    <br><span class="text-muted" style="font-size: 11px;">{{ $u->nomor_telepon }}</span>
                                @endif
                            </td>
                            <td><span class="badge bg-dark">{{ strtoupper($u->role) }}</span></td>
                            <td>
                                @if($u->tipe_penilai === 'lokal')
                                    <span class="badge-otentik">Warga Lokal (1.5x)</span>
                                @else
                                    <span class="badge bg-light text-dark border">Wisatawan (1.0x)</span>
                                @endif
                            </td>
                            <td>
                                @if($u->is_aktif)
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-slash-circle me-1"></i>Diblokir</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-toba-secondary" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $u->id }}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                @if($u->id !== auth()->id())
                                    <form action="{{ route('admin.pengguna.blokir', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ $u->is_aktif ? 'Blokir akses pengguna ini?' : 'Aktifkan kembali akun pengguna ini?' }}')">
                                        @csrf @method('PATCH')
                                        @if($u->is_aktif)
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Blokir Akses Pengguna">
                                                <i class="bi bi-lock-fill"></i> Blokir
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Aktifkan Kembali">
                                                <i class="bi bi-unlock-fill"></i> Aktifkan
                                            </button>
                                        @endif
                                    </form>
                                    <form action="{{ route('admin.pengguna.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus permanen pengguna ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Hapus Akun"><i class="bi bi-trash"></i></button>
                                    </form>
                                @else
                                    <span class="badge bg-success ms-1">Anda (Admin Aktif)</span>
                                @endif

                                <!-- Modal Edit Pengguna -->
                                <div class="modal fade text-start" id="modalEditUser{{ $u->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="{{ route('admin.pengguna.update', $u) }}" method="POST" class="modal-content rounded-4 border-0">
                                            @csrf @method('PUT')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Edit Pengguna: {{ $u->nama }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Nama Lengkap *</label>
                                                    <input type="text" name="nama" value="{{ $u->nama }}" class="form-control" required>
                                                </div>
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label small fw-bold">Peran Sistem</label>
                                                        <select name="role" class="form-select">
                                                            <option value="wisatawan" {{ $u->role === 'wisatawan' ? 'selected' : '' }}>Wisatawan / Penilai</option>
                                                            <option value="umkm" {{ $u->role === 'umkm' ? 'selected' : '' }}>Mitra Toko UMKM</option>
                                                            <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Administrator / Kurator</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-bold">Bobot Penilai</label>
                                                        <select name="tipe_penilai" class="form-select">
                                                            <option value="wisatawan" {{ $u->tipe_penilai === 'wisatawan' ? 'selected' : '' }}>Wisatawan (1.0x)</option>
                                                            <option value="lokal" {{ $u->tipe_penilai === 'lokal' ? 'selected' : '' }}>Warga Lokal (1.5x)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                @if($u->id !== auth()->id())
                                                    <div>
                                                        <label class="form-label small fw-bold">Status Akses Akun</label>
                                                        <select name="is_aktif" class="form-select">
                                                            <option value="1" {{ $u->is_aktif ? 'selected' : '' }}>Aktif (Dapat Mengakses Sistem)</option>
                                                            <option value="0" {{ !$u->is_aktif ? 'selected' : '' }}>Diblokir / Dinonaktifkan</option>
                                                        </select>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-toba-primary w-100">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengguna Baru -->
<div class="modal fade" id="modalTambahPengguna" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.pengguna.store') }}" method="POST" class="modal-content rounded-4 border-0">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Lengkap *</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Email *</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Nomor Telepon</label>
                        <input type="text" name="nomor_telepon" class="form-control">
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Peran Sistem *</label>
                        <select name="role" class="form-select" required>
                            <option value="wisatawan">Wisatawan / Penilai</option>
                            <option value="umkm">Mitra Toko UMKM</option>
                            <option value="admin">Administrator / Kurator</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Bobot Penilai *</label>
                        <select name="tipe_penilai" class="form-select" required>
                            <option value="lokal">Warga Lokal (1.5x)</option>
                            <option value="wisatawan">Wisatawan (1.0x)</option>
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold">Kata Sandi Awal (Min. 8 Karakter) *</label>
                    <input type="password" name="password" class="form-control" required minlength="8">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-toba-primary w-100">Buat Akun Pengguna</button>
            </div>
        </form>
    </div>
</div>
@endsection
