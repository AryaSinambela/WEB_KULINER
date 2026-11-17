@extends('layouts.app')

@section('title', 'Status Verifikasi Usaha — Portal Mitra')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card-toba p-4 p-lg-5 text-center">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px; background-color: var(--toba-secondary-light); color: var(--toba-primary);">
                    <i class="bi bi-hourglass-split fs-3"></i>
                </div>
                <span class="badge-otentik mb-2">TAHAP KURASI ADMINISTRATOR</span>
                <h3 class="fw-bold mb-2">Usaha Anda Sedang Dalam Peninjauan</h3>
                <p class="text-muted small mb-4">
                    Tim Admin &amp; Kurator sedang memverifikasi titik koordinat GPS, kelengkapan profil usaha, dan daftar menu <strong>{{ $destinasi->nama ?? 'Anda' }}</strong> sebelum ditampilkan secara publik di Peta Kuliner Toba.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('umkm.usaha.edit') }}" class="btn btn-toba-primary px-4">Lengkapi Profil &amp; Koordinat</a>
                    <a href="{{ route('umkm.menu.index') }}" class="btn btn-toba-secondary px-4">Tambah Daftar Menu</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection