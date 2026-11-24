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
        @forelse($destinasi->menus as $idx => $menu)
            @php
                $ceritaMenu = $destinasi->ceritaBudaya->first(function($c) use ($menu) {
                    return str_contains(strtolower($c->judul), strtolower($menu->nama_menu));
                }) ?? $destinasi->ceritaBudaya->values()->get($idx);
            @endphp
            <div class="col-md-6">
                <div class="card-toba h-100 overflow-hidden d-flex flex-column">
                    <div class="destinasi-card-img-wrap" style="height: 175px;">
                        <div class="destinasi-placeholder-fallback">
                            <div class="placeholder-icon-circle">
                                <i class="bi bi-cup-hot"></i>
                            </div>
                            <span class="placeholder-title">{{ $menu->nama_menu }}</span>
                        </div>
                        @if(!empty($menu->url_foto))
                            <img src="{{ $menu->url_foto }}" 
                                 class="destinasi-card-img border-bottom" 
                                 alt="{{ $menu->nama_menu }}"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.style.display='none';">
                        @endif
                    </div>
                    <div class="p-3 d-flex flex-column flex-grow-1 justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                <h6 class="fw-bold mb-0">{{ $menu->nama_menu }}</h6>
                                <span class="badge bg-dark rounded-pill">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-muted small mb-2">{{ $menu->deskripsi }}</p>

                            @if($ceritaMenu)
                                <div class="p-2 rounded-3 border mb-3 small" style="background-color: var(--toba-neutral);">
                                    <span class="badge-otentik mb-1 d-inline-block" style="font-size: 9.5px;">CERITA BUDAYA MENU</span>
                                    <strong class="d-block text-dark">{{ $ceritaMenu->judul }}</strong>
                                    <span class="text-muted d-block" style="font-size: 11.5px;">{{ $ceritaMenu->asal_usul }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-toba-secondary btn-sm flex-grow-1" data-bs-toggle="modal" data-bs-target="#modalEditMenu{{ $menu->id }}">
                                <i class="bi bi-pencil-square me-1"></i> Edit Menu &amp; Cerita
                            </button>
                            <form action="{{ route('umkm.menu.destroy', $menu) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Edit Menu & Cerita Budaya -->
            <div class="modal fade" id="modalEditMenu{{ $menu->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <form action="{{ route('umkm.menu.update', $menu) }}" method="POST" enctype="multipart/form-data" class="modal-content rounded-4 border-0">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="cerita_id" value="{{ $ceritaMenu->id ?? '' }}">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Edit Menu &amp; Cerita Budaya</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3 mb-3">
                                <div class="col-md-8">
                                    <label class="form-label small fw-bold">Nama Menu Hidangan *</label>
                                    <input type="text" name="nama_menu" value="{{ $menu->nama_menu }}" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Harga per Porsi (Rp) *</label>
                                    <input type="number" name="harga" value="{{ $menu->harga }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Deskripsi &amp; Bahan Utama</label>
                                <textarea name="deskripsi" rows="2" class="form-control">{{ $menu->deskripsi }}</textarea>
                            </div>
                            <hr>
                            <h6 class="fw-bold small text-uppercase" style="color: var(--toba-primary);"><i class="bi bi-book-half me-1"></i> Cerita Budaya untuk Menu Ini</h6>
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Asal-Usul &amp; Sejarah Menu</label>
                                <textarea name="asal_usul" rows="2" class="form-control">{{ $ceritaMenu->asal_usul ?? '' }}</textarea>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Makna dalam Adat Batak</label>
                                    <input type="text" name="makna_adat" value="{{ $ceritaMenu->makna_adat ?? '' }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Istilah Lokal</label>
                                    <input type="text" name="istilah_lokal" value="{{ $ceritaMenu->istilah_lokal ?? '' }}" class="form-control">
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Ganti Foto Hidangan (Opsional)</label>
                                <input type="file" name="path_foto" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-toba-primary w-100">Simpan Perubahan Menu &amp; Cerita</button>
                        </div>
                    </form>
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

<!-- Modal Tambah Menu & Cerita Budaya -->
<div class="modal fade" id="modalTambahMenu" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('umkm.menu.store') }}" method="POST" enctype="multipart/form-data" class="modal-content rounded-4 border-0">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Menu Kuliner &amp; Cerita Budaya</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label class="form-label small fw-bold">Nama Menu Hidangan *</label>
                        <input type="text" name="nama_menu" class="form-control" placeholder="Contoh: Arsik Ikan Mas Bumbu Andaliman" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Harga per Porsi (Rp) *</label>
                        <input type="number" name="harga" class="form-control" placeholder="35000" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Deskripsi &amp; Bahan Utama</label>
                    <textarea name="deskripsi" rows="2" class="form-control" placeholder="Jelaskan bahan rempah dan cara penyajiannya..."></textarea>
                </div>
                <hr>
                <h6 class="fw-bold small text-uppercase" style="color: var(--toba-primary);"><i class="bi bi-book-half me-1"></i> Cerita Budaya untuk Menu Ini</h6>
                <div class="mb-2">
                    <label class="form-label small fw-bold">Asal-Usul &amp; Sejarah Menu</label>
                    <textarea name="asal_usul" rows="2" class="form-control" placeholder="Ceritakan sejarah atau keunikan resep menu ini di toko Anda..."></textarea>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Makna dalam Adat Batak</label>
                        <input type="text" name="makna_adat" class="form-control" placeholder="Contoh: Simbol doa keberkahan keluarga">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Istilah Lokal</label>
                        <input type="text" name="istilah_lokal" class="form-control" placeholder="Contoh: Marsik = dimasak hingga meresap kering">
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold">Foto Hidangan (Opsional)</label>
                    <input type="file" name="path_foto" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-toba-primary w-100">Simpan Menu &amp; Cerita Budaya</button>
            </div>
        </form>
    </div>
</div>
@endsection