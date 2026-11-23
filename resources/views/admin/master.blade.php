@extends('layouts.app')

@section('title', 'Master Data Kategori & Wilayah — Konsol Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <small class="fw-bold text-uppercase" style="color: var(--toba-primary);">TAKSONOMI &amp; GEOSPASIAL KALDERA</small>
            <h1 class="h3 fw-bold mb-0">Master Data: Kategori Kuliner &amp; Wilayah</h1>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-toba-secondary btn-sm">Kembali ke Dashboard</a>
    </div>

    <div class="row g-4">
        <!-- Kelola Kategori Makanan -->
        <div class="col-lg-6">
            <div class="card-toba p-4">
                <h5 class="fw-bold mb-3">Kategori Kuliner ({{ $kategoris->count() }})</h5>
                <form action="{{ route('admin.kategori.store') }}" method="POST" class="input-group mb-3">
                    @csrf
                    <input type="text" name="nama" class="form-control" placeholder="Nama kategori baru..." required>
                    <button type="submit" class="btn btn-toba-primary">Tambah</button>
                </form>

                <ul class="list-group list-group-flush small">
                    @foreach($kategoris as $kat)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><strong>{{ $kat->nama }}</strong> <span class="text-muted">({{ $kat->destinasi_count }} toko)</span></span>
                            <div class="d-flex gap-2 align-items-center">
                                <button type="button" class="btn btn-sm btn-link text-decoration-none p-0" style="color: var(--toba-primary);" data-bs-toggle="modal" data-bs-target="#modalEditKat{{ $kat->id }}">Edit</button>
                                @if($kat->destinasi_count === 0)
                                    <form action="{{ route('admin.kategori.destroy', $kat) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none p-0">Hapus</button>
                                    </form>
                                @endif
                            </div>

                            <!-- Modal Edit Kategori -->
                            <div class="modal fade" id="modalEditKat{{ $kat->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <form action="{{ route('admin.kategori.update', $kat) }}" method="POST" class="modal-content rounded-4 border-0">
                                        @csrf @method('PUT')
                                        <div class="modal-header">
                                            <h6 class="modal-title fw-bold">Edit Kategori</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" name="nama" value="{{ $kat->nama }}" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-toba-primary btn-sm w-100">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Kelola Wilayah Kecamatan & Kabupaten -->
        <div class="col-lg-6">
            <div class="card-toba p-4">
                <h5 class="fw-bold mb-3">Struktur Wilayah Kaldera Toba ({{ $wilayahs->count() }})</h5>
                <form action="{{ route('admin.wilayah.store') }}" method="POST" class="row g-2 mb-3">
                    @csrf
                    <div class="col-5">
                        <input type="text" name="nama_kecamatan" class="form-control form-control-sm" placeholder="Kecamatan" required>
                    </div>
                    <div class="col-4">
                        <input type="text" name="kabupaten" class="form-control form-control-sm" placeholder="Kabupaten" required>
                    </div>
                    <div class="col-3">
                        <button type="submit" class="btn btn-toba-primary btn-sm w-100">Tambah</button>
                    </div>
                </form>

                <ul class="list-group list-group-flush small">
                    @foreach($wilayahs as $w)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><strong>{{ $w->nama_kecamatan }}</strong> — Kab. {{ $w->kabupaten }} <span class="text-muted">({{ $w->destinasi_count }} toko)</span></span>
                            <div class="d-flex gap-2 align-items-center">
                                <button type="button" class="btn btn-sm btn-link text-decoration-none p-0" style="color: var(--toba-primary);" data-bs-toggle="modal" data-bs-target="#modalEditWil{{ $w->id }}">Edit</button>
                                @if($w->destinasi_count === 0)
                                    <form action="{{ route('admin.wilayah.destroy', $w) }}" method="POST" onsubmit="return confirm('Hapus wilayah ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none p-0">Hapus</button>
                                    </form>
                                @endif
                            </div>

                            <!-- Modal Edit Wilayah -->
                            <div class="modal fade" id="modalEditWil{{ $w->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <form action="{{ route('admin.wilayah.update', $w) }}" method="POST" class="modal-content rounded-4 border-0">
                                        @csrf @method('PUT')
                                        <div class="modal-header">
                                            <h6 class="modal-title fw-bold">Edit Wilayah</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-2">
                                                <label class="form-label small fw-bold">Kecamatan</label>
                                                <input type="text" name="nama_kecamatan" value="{{ $w->nama_kecamatan }}" class="form-control form-control-sm" required>
                                            </div>
                                            <div>
                                                <label class="form-label small fw-bold">Kabupaten</label>
                                                <input type="text" name="kabupaten" value="{{ $w->kabupaten }}" class="form-control form-control-sm" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-toba-primary btn-sm w-100">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection