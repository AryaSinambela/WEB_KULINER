@extends('layouts.app')

@section('title', 'Pusat Notifikasi — Toba Kuliner')

@section('content')
<div class="container py-4" style="max-width: 780px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-0">Pusat Notifikasi</h1>
            <p class="text-muted small mb-0">Pembaruan status kurasi, ulasan toko, dan aktivitas akun Anda.</p>
        </div>
        @if($notifikasis->count() > 0)
            <form action="{{ route('notifikasi.baca-semua') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-toba-secondary btn-sm px-3">
                    <i class="bi bi-check2-all me-1"></i> Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    <div class="d-flex flex-column gap-3">
        @forelse($notifikasis as $notif)
            <div class="card-toba p-3 d-flex justify-content-between align-items-start {{ !$notif->dibaca ? 'border-danger' : '' }}">
                <div class="d-flex gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width: 40px; height: 40px; background-color: var(--toba-neutral); color: var(--toba-primary);">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h6 class="fw-bold mb-0">{{ $notif->judul }}</h6>
                            @if(!$notif->dibaca)
                                <span class="badge bg-danger" style="font-size: 10px;">Baru</span>
                            @endif
                        </div>
                        <p class="text-muted small mb-1">{{ $notif->pesan }}</p>
                        <small class="text-muted" style="font-size: 11px;">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        @empty
            <div class="card-toba p-5 text-center text-muted">
                <i class="bi bi-bell-slash fs-1 d-block mb-2"></i>
                <h6 class="fw-bold text-dark">Belum Ada Notifikasi</h6>
                <p class="small mb-3">Semua pemberitahuan aktivitas akun dan verifikasi toko akan muncul di sini.</p>
                <a href="{{ route('jelajah') }}" class="btn btn-toba-primary btn-sm px-4">Jelajahi Toko Kuliner</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
