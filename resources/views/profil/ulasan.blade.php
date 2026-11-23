@extends('layouts.app')

@section('title', 'Riwayat Ulasan Saya — Toba Kuliner')

@section('content')
<div class="container py-4" style="max-width: 820px;">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 fw-bold mb-0">Riwayat Ulasan Kuliner Saya</h1>
            <p class="text-muted small mb-0">Daftar penilaian dan kesaksian rasa yang telah Anda berikan pada toko kuliner Toba.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('profil.edit') }}" class="btn btn-toba-secondary btn-sm px-3">Profil Saya</a>
            <a href="{{ route('profil.keamanan') }}" class="btn btn-toba-secondary btn-sm px-3">Keamanan Sandi</a>
            <a href="{{ route('profil.ulasan') }}" class="btn btn-toba-primary btn-sm px-3">Riwayat Ulasan</a>
        </div>
    </div>

    <div class="d-flex flex-column gap-3">
        @forelse($ulasans as $u)
            <div class="card-toba p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <small class="text-uppercase fw-bold text-danger" style="font-size: 10px;"><i class="bi bi-shop"></i> TOKO KULINER</small>
                        <h5 class="fw-bold mb-0">
                            <a href="{{ route('destinasi.show', $u->destinasi_id) }}" class="text-decoration-none text-dark">
                                {{ $u->destinasi->nama ?? 'Toko Kuliner' }}
                            </a>
                        </h5>
                        <small class="text-muted">{{ $u->destinasi->wilayah->nama_kecamatan ?? '' }}, {{ $u->destinasi->wilayah->kabupaten ?? '' }}</small>
                    </div>
                    <span class="badge bg-warning text-dark fs-6">★ {{ $u->rating_umum }}.0</span>
                </div>
                <div class="d-flex flex-wrap gap-3 small text-muted mb-2">
                    @if($u->rating_rasa) <span>Rasa: <strong>{{ $u->rating_rasa }}/5</strong></span> @endif
                    @if($u->rating_harga) <span>Harga: <strong>{{ $u->rating_harga }}/5</strong></span> @endif
                    @if($u->rating_keotentikan) <span>Keotentikan: <strong>{{ $u->rating_keotentikan }}/5</strong></span> @endif
                </div>
                <p class="small text-dark mb-2">{{ $u->komentar }}</p>
                @if($u->fotos && $u->fotos->count() > 0)
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($u->fotos as $f)
                            <img src="{{ asset('storage/' . $f->path_foto) }}" class="rounded-3 border object-fit-cover" style="width: 72px; height: 72px;" alt="Foto Ulasan">
                        @endforeach
                    </div>
                @endif
                <div class="d-flex justify-content-between align-items-center pt-2 border-top small">
                    <span class="text-muted">{{ \Carbon\Carbon::parse($u->created_at)->diffForHumans() }}</span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('destinasi.show', $u->destinasi_id) }}" class="btn btn-sm btn-toba-secondary">Lihat Toko</a>
                        <a href="{{ route('ulasan.edit', $u) }}" class="btn btn-sm btn-toba-outlined"><i class="bi bi-pencil-square me-1"></i> Edit Ulasan</a>
                        <form action="{{ route('ulasan.destroy', $u) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="card-toba p-5 text-center text-muted">
                <i class="bi bi-chat-square-heart fs-1 d-block mb-2"></i>
                <h6 class="fw-bold text-dark">Belum Ada Ulasan</h6>
                <p class="small mb-3">Anda belum memberikan ulasan pada toko kuliner mana pun.</p>
                <a href="{{ route('jelajah') }}" class="btn btn-toba-primary btn-sm px-4">Mulai Jelajah &amp; Ulas Toko</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
