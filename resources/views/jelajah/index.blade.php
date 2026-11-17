@extends('layouts.app')

@section('title', 'Jelajah Ragam Kuliner Tradisional Danau Toba')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="small mb-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item active fw-semibold text-dark" aria-current="page">Jelajah Destinasi</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3">
        <div>
            <small class="fw-bold text-uppercase" style="color: var(--toba-primary);">KURASI CITA RASA KALDERA TOBA</small>
            <h1 class="h2 fw-bold mb-1">Jelajah Ragam Kuliner Tradisional Danau Toba</h1>
            <p class="text-muted small mb-0">Temukan warung, lapau, rumah makan, dan kedai kopi terverifikasi di sekeliling Danau Toba.</p>
        </div>
        <div class="btn-group" role="group" aria-label="Mode Tampilan">
            <button type="button" id="btnGrid" class="btn btn-toba-primary btn-sm px-3"><i class="bi bi-grid-fill me-1"></i> Kisi (Grid)</button>
            <button type="button" id="btnList" class="btn btn-toba-secondary btn-sm px-3"><i class="bi bi-list-ul me-1"></i> Daftar (List)</button>
        </div>
    </div>

    <!-- Form Pencarian & Filter -->
    <form action="{{ route('jelajah') }}" method="GET" class="card-toba p-3 mb-4">
        <div class="row g-2 align-items-center mb-3">
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control bg-light border-0 shadow-none" placeholder="Cari nama makanan (mie gomak, arsik, andaliman) atau nama kedai...">
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <select name="kabupaten" class="form-select bg-light border-0 shadow-none">
                    <option value="">Semua Wilayah Kabupaten</option>
                    @foreach($kabupatens as $kab)
                        <option value="{{ $kab }}" {{ request('kabupaten') === $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-lg-3 d-flex gap-2">
                <button type="submit" class="btn btn-toba-primary w-100">Cari Kuliner <i class="bi bi-arrow-right ms-1"></i></button>
                @if(request()->anyFilled(['q', 'kategori_id', 'kabupaten', 'otentik', 'sort']))
                    <a href="{{ route('jelajah') }}" class="btn btn-toba-secondary" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
                @endif
            </div>
        </div>

        <!-- Baris Filter Kategori & Otentik -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-2 border-top" style="border-color: var(--toba-border) !important;">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('jelajah', request()->except('kategori_id')) }}" 
                   class="btn btn-sm rounded-pill px-3 {{ !request('kategori_id') ? 'btn-toba-primary' : 'btn-toba-secondary' }}">
                    Semua
                </a>
                @foreach($kategoris as $kat)
                    <a href="{{ route('jelajah', array_merge(request()->query(), ['kategori_id' => $kat->id])) }}" 
                       class="btn btn-sm rounded-pill px-3 {{ request('kategori_id') == $kat->id ? 'btn-toba-primary' : 'btn-toba-secondary' }}">
                        {{ $kat->nama }} ({{ $kat->destinasi_count }})
                    </a>
                @endforeach
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" id="filterOtentik" name="otentik" value="1" 
                           {{ request()->boolean('otentik') ? 'checked' : '' }} onchange="this.form.submit()">
                    <label class="form-check-label small fw-semibold" for="filterOtentik">
                        <span class="badge-otentik"><i class="bi bi-patch-check-fill text-warning"></i> Otentik Batak Terverifikasi</span>
                    </label>
                </div>

                <select name="sort" class="form-select form-select-sm border-0 bg-light w-auto" onchange="this.form.submit()">
                    <option value="rekomendasi" {{ request('sort') === 'rekomendasi' ? 'selected' : '' }}>Paling Direkomendasikan</option>
                    <option value="terbaru" {{ request('sort') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="harga_asc" {{ request('sort') === 'harga_asc' ? 'selected' : '' }}>Harga Terendah</option>
                </select>
            </div>
        </div>
    </form>

    <!-- Info Jumlah Hasil -->
    <div class="d-flex justify-content-between align-items-center mb-3 small text-muted">
        <span>Menampilkan <strong>{{ $destinasiList->firstItem() ?? 0 }} - {{ $destinasiList->lastItem() ?? 0 }}</strong> dari <strong>{{ $destinasiList->total() }}</strong> destinasi kuliner</span>
        <a href="{{ route('peta', request()->query()) }}" class="text-decoration-none fw-semibold" style="color: var(--toba-primary);">
            <i class="bi bi-map me-1"></i> Lihat di Peta Interaktif
        </a>
    </div>

    <!-- Daftar Destinasi -->
    <div class="row g-4" id="destinasiContainer">
        @forelse($destinasiList as $item)
            <div class="col-md-6 col-lg-3 destinasi-col">
                <div class="card-toba h-100 overflow-hidden d-flex flex-column destinasi-card">
                    <div class="position-relative destinasi-img-wrap">
                        <img src="{{ $item->fotoUtama ? asset('storage/' . $item->fotoUtama->path_foto) : 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=600&q=80' }}" 
                             class="w-100 object-fit-cover" style="height: 185px;" alt="{{ $item->nama }}" loading="lazy">
                        <div class="position-absolute top-0 start-0 m-2 d-flex flex-wrap gap-1">
                            @if($item->is_otentik_batak)
                                <span class="badge-otentik shadow-sm">★ Otentik Batak</span>
                            @endif
                            <span class="badge bg-dark bg-opacity-75 rounded-pill" style="font-size: 11px;">{{ $item->kategori->nama ?? 'Kuliner' }}</span>
                        </div>
                    </div>

                    <div class="p-3 d-flex flex-column flex-grow-1 justify-content-between">
                        <div>
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-geo-alt-fill text-danger"></i> {{ $item->wilayah->nama_kecamatan ?? '' }}, {{ $item->wilayah->kabupaten ?? '' }}
                            </small>
                            <h6 class="fw-bold mb-1">{{ $item->nama }}</h6>
                            <p class="text-muted small mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $item->deskripsi ?? 'Sajian kuliner khas kawasan Danau Toba dengan racikan bumbu tradisional.' }}
                            </p>
                        </div>

                        <div>
                            <!-- Dual Rating Box -->
                            <div class="d-flex justify-content-between align-items-center p-2 rounded-3 mb-2 small" style="background-color: var(--toba-neutral);">
                                <div>
                                    <span class="text-muted d-block" style="font-size: 10px;">Wisatawan</span>
                                    <span class="fw-bold text-dark"><i class="bi bi-star-fill text-warning"></i> {{ $item->rating_wisatawan ?: '-' }}</span>
                                </div>
                                <div class="vr"></div>
                                <div class="text-end">
                                    <span class="text-muted d-block" style="font-size: 10px;">Lokal (1.5x)</span>
                                    <span class="fw-bold" style="color: var(--toba-primary);"><i class="bi bi-patch-check-fill text-warning"></i> {{ $item->rating_lokal ?: '-' }}</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center small mb-2">
                                <span class="text-muted">Kisaran:</span>
                                <span class="fw-bold text-dark">
                                    Rp {{ number_format($item->harga_min ?? 15000, 0, ',', '.') }}
                                    @if($item->harga_max) - {{ number_format($item->harga_max, 0, ',', '.') }} @endif
                                </span>
                            </div>

                            <a href="{{ route('destinasi.show', $item) }}" class="btn btn-toba-primary btn-sm w-100 py-2">
                                Lihat Detail &amp; Cerita
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card-toba p-5 text-center">
                    <i class="bi bi-search fs-1 text-muted mb-2"></i>
                    <h5 class="fw-bold">Destinasi Kuliner Tidak Ditemukan</h5>
                    <p class="text-muted small">Coba ubah kata kunci pencarian atau reset filter wilayah/kategori Anda.</p>
                    <a href="{{ route('jelajah') }}" class="btn btn-toba-primary btn-sm px-4">Tampilkan Semua Destinasi</a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $destinasiList->links() }}
    </div>

    <!-- Banner Edukasi Dual Rating (Sesuai Mockup Screen 6) -->
    <div class="card-toba p-4 mt-5" style="background-color: #F6F1E3;">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <small class="fw-bold text-uppercase" style="color: var(--toba-primary);">METODOLOGI KURASI TOBA KULINER</small>
                <h4 class="fw-bold mt-1 mb-2">Mengapa Penilaian Warga Lokal Diberi Bobot 1.5x?</h4>
                <p class="text-muted small mb-0">
                    Dalam tradisi Batak, keaslian rasa tidak semata soal bumbu sedap di lidah pelancong, melainkan kesetiaan pada resep leluhur yang diwariskan dalam filosofi <em>Dalihan Na Tolu</em>. Lidah masyarakat lokal memahami takaran andaliman segar (<em>Zanthoxylum acanthopodium</em>), kematangan ikan mas tanpa bau lumpur, serta keotentikan asam rias tanpa penyedap instan.
                </p>
            </div>
            <div class="col-lg-4">
                <div class="bg-white p-3 rounded-4 border">
                    <div class="d-flex justify-content-between small mb-1">
                        <span>Wisatawan Luar</span>
                        <span class="fw-bold">Bobot 1.0x</span>
                    </div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-secondary" style="width: 66%;"></div>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="fw-bold" style="color: var(--toba-primary);">Warga Lokal Batak</span>
                        <span class="fw-bold" style="color: var(--toba-primary);">Bobot 1.5x</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" style="width: 100%; background-color: var(--toba-primary);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle Grid vs List View
    const btnGrid = document.getElementById('btnGrid');
    const btnList = document.getElementById('btnList');
    const cols = document.querySelectorAll('.destinasi-col');

    btnList?.addEventListener('click', () => {
        cols.forEach(c => {
            c.classList.remove('col-md-6', 'col-lg-3');
            c.classList.add('col-12');
        });
        btnList.classList.replace('btn-toba-secondary', 'btn-toba-primary');
        btnGrid.classList.replace('btn-toba-primary', 'btn-toba-secondary');
    });

    btnGrid?.addEventListener('click', () => {
        cols.forEach(c => {
            c.classList.remove('col-12');
            c.classList.add('col-md-6', 'col-lg-3');
        });
        btnGrid.classList.replace('btn-toba-secondary', 'btn-toba-primary');
        btnList.classList.replace('btn-toba-primary', 'btn-toba-secondary');
    });
</script>
@endpush