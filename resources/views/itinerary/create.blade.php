@extends('layouts.app')

@section('title', 'Buat Rencana Kunjungan Baru — Toba Kuliner')

@section('content')
<div class="container py-4" style="max-width: 720px;">
    <div class="mb-4">
        <a href="{{ route('itinerary.index') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left"></i> Kembali ke Daftar Rencana</a>
        <h1 class="h3 fw-bold mb-0 mt-1">Susun Rencana Perjalanan Kuliner &amp; Wisata Toba</h1>
    </div>

    <div class="card-toba p-4">
        <form action="{{ route('itinerary.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-bold">Judul Rencana Perjalanan *</label>
                <input type="text" name="judul" class="form-control" placeholder="Contoh: Jelajah Kuliner Balige & Samosir 2 Hari" required>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Tanggal Mulai Kunjungan</label>
                    <input type="date" name="tanggal_kunjungan" value="{{ date('Y-m-d') }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Moda Transportasi</label>
                    <select name="moda" class="form-select">
                        <option value="Mobil">Mobil Pribadi / Rental</option>
                        <option value="Motor">Sepeda Motor</option>
                        <option value="Kapal & Mobil">Penyeberangan Kapal Ferry &amp; Mobil</option>
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">Pilih Toko / Destinasi Kuliner Pertama (Opsional)</label>
                <select name="destinasi_id" class="form-select">
                    <option value="">-- Pilih Toko Kuliner --</option>
                    @foreach($destinasiList as $d)
                        <option value="{{ $d->id }}">{{ $d->nama }} ({{ $d->wilayah->nama_kecamatan ?? '' }}, {{ $d->wilayah->kabupaten ?? '' }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-toba-primary w-100 py-2">Buat Rencana &amp; Atur Rute Peta</button>
        </form>
    </div>
</div>
@endsection
