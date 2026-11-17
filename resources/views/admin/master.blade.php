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
                            <span><strong>{{ $kat->nama }}</strong> <span class="text-muted">({{ $kat->destinasi_count }} destinasi)</span></span>
                            @if($kat->destinasi_count === 0)
                                <form action="{{ route('admin.kategori.destroy', $kat) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0">Hapus</button>
                                </form>
                            @endif
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
                            <span><strong>{{ $w->nama_kecamatan }}</strong> — Kab. {{ $w->kabupaten }}</span>
                            @if($w->destinasi_count === 0)
                                <form action="{{ route('admin.wilayah.destroy', $w) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0">Hapus</button>
                                </form>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection