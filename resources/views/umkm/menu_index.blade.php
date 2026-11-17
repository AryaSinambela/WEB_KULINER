@extends('layouts.app')

@section('title', 'Kelola Menu & Harga — ' . $destinasi->nama)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('umkm.dashboard') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
            <h1 class="h3 fw-bold mb-0 mt-1">Kelola Daftar Menu &amp; Harga</h1>
        </div>
        <button class="btn btn-toba-primary px-4" data-bs-toggle="modal" data-bs-target="#modalTambahMenu">
            <i class="bi bi-plus-lg me-1"></i> Tambah Menu Baru
        </button>
    </div>

    <div class="row g-4">
        @forelse($destinasi->menus as $menu)
            <div class="col-md-6 col-lg-4">
                <div class="card-toba h-100 overflow-hidden d-flex flex-column">
                    @if($menu->path_foto)
                        <img src="{{ asset('storage/' . $menu->path_foto) }}" class="w-100 object-fit-cover" style="height: 170px;" alt="{{ $menu->nama_menu }}">
                    @endif
                    <div class="p-3 d-flex flex-column flex-grow-1 justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                <h6 class="fw-bold mb-0">{{ $menu->nama_menu }}</h6>
                                <span class="badge bg-dark rounded-pill">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-muted small mb-3">{{ $menu->deskripsi }}</p>
                        </div>
                        <form action="{{ route('umkm.menu.destroy', $menu) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100"><i class="bi bi-trash me-1"></i> Hapus Menu</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card-toba p-5 text-center text-muted">
                    Belum ada menu yang ditambahkan. Klik tombol <strong>Tambah Menu Baru</strong> di kanan atas.
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah Menu -->
<div class="modal fade" id="modalTambahMenu" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('umkm.menu.store') }}" method="POST" enctype="multipart/form-data" class="modal-content rounded-4 border-0">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Menu Kuliner Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Menu Hidangan *</label>
                    <input type="text" name="nama_menu" class="form-control" placeholder="Contoh: Arsik Ikan Mas Bumbu Andaliman" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Harga per Porsi (Rp) *</label>
                    <input type="number" name="harga" class="form-control" placeholder="25000" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Deskripsi &amp; Bahan Utama</label>
                    <textarea name="deskripsi" rows="3" class="form-control" placeholder="Jelaskan bahan rempah dan cara penyajiannya..."></textarea>
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold">Foto Hidangan (Opsional)</label>
                    <input type="file" name="path_foto" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-toba-primary w-100">Simpan Menu</button>
            </div>
        </form>
    </div>
</div>
@endsection