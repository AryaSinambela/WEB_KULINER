@extends('layouts.app')

@section('title', 'Profil & Log Kurator — Konsol Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard Admin</a>
            <h1 class="h3 fw-bold mb-0 mt-1">Profil Dewan Kurator &amp; Riwayat Verifikasi Toko</h1>
        </div>
        <a href="{{ route('admin.profil.log') }}" class="btn btn-toba-primary btn-sm px-3">
            <i class="bi bi-download me-1"></i> Unduh Log Kurasi (CSV)
        </a>
    </div>

    <div class="card-toba p-4 mb-4">
        <div class="row g-3 small">
            <div class="col-md-4">
                <span class="text-muted d-block">Nama Kurator:</span>
                <strong class="fs-6">{{ $admin->nama }}</strong>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block">Email Resmi:</span>
                <strong>{{ $admin->email }}</strong>
            </div>
            <div class="col-md-4">
                <span class="text-muted d-block">Otoritas:</span>
                <span class="badge-otentik">Dewan Kurator Adat &amp; Gastronomi Toba</span>
            </div>
        </div>
    </div>

    <div class="card-toba p-4">
        <h5 class="fw-bold mb-3">Log Verifikasi Toko Kuliner ({{ $logVerifikasi->count() }} Toko)</h5>
        <div class="table-responsive">
            <table class="table table-sm align-middle small">
                <thead class="table-light">
                    <tr>
                        <th>Nama Toko</th>
                        <th>Wilayah</th>
                        <th>Status</th>
                        <th>Label Otentik</th>
                        <th>Waktu Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logVerifikasi as $log)
                        <tr>
                            <td class="fw-semibold"><a href="{{ route('destinasi.show', $log) }}" class="text-decoration-none text-dark">{{ $log->nama }}</a></td>
                            <td>{{ $log->wilayah->nama_kecamatan ?? '' }}, {{ $log->wilayah->kabupaten ?? '' }}</td>
                            <td><span class="badge bg-success">{{ strtoupper($log->status_verifikasi) }}</span></td>
                            <td>{!! $log->is_otentik_batak ? '<span class="badge bg-warning text-dark">★ Otentik Batak</span>' : '-' !!}</td>
                            <td>{{ $log->diverifikasi_pada }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
