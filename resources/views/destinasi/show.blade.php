@extends('layouts.app')

@section('title', $destinasi->nama . ' — Detail & Cerita Budaya')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="small mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('jelajah') }}" class="text-decoration-none text-muted">Jelajah Destinasi</a></li>
            <li class="breadcrumb-item active fw-semibold text-dark">{{ $destinasi->nama }}</li>
        </ol>
    </nav>

    <!-- Header Card Destinasi -->
    <div class="card-toba p-4 mb-4">
        <div class="row g-4 align-items-start">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap gap-2 mb-2">
                    @if($destinasi->is_otentik_batak)
                        <span class="badge-otentik"><i class="bi bi-award-fill text-warning me-1"></i> Otentik Batak Terverifikasi</span>
                    @endif
                    <span class="badge bg-light text-dark border">{{ $destinasi->kategori->nama ?? 'Kuliner Toba' }}</span>
                </div>

                <h1 class="h2 fw-bold mb-2">{{ $destinasi->nama }}</h1>
                <p class="text-muted mb-3">{{ $destinasi->deskripsi }}</p>

                <div class="row g-3 pt-3 border-top small" style="border-color: var(--toba-border) !important;">
                    <div class="col-6 col-md-3">
                        <span class="text-muted d-block"><i class="bi bi-geo-alt-fill text-danger"></i> LOKASI</span>
                        <strong class="text-dark">{{ $destinasi->wilayah->nama_kecamatan ?? '' }}, {{ $destinasi->wilayah->kabupaten ?? '' }}</strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <span class="text-muted d-block"><i class="bi bi-clock-fill text-success"></i> JAM BUKA</span>
                        <strong class="text-dark">
                            {{ $destinasi->jam_buka ? substr($destinasi->jam_buka, 0, 5) : '08:00' }} - {{ $destinasi->jam_tutup ? substr($destinasi->jam_tutup, 0, 5) : '20:00' }} WIB
                        </strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <span class="text-muted d-block"><i class="bi bi-star-fill text-warning"></i> RATING WISATAWAN</span>
                        <strong class="text-dark fs-6">{{ $destinasi->rating_wisatawan ?: '0.0' }}</strong>
                        <span class="text-muted">({{ $destinasi->ulasans->where('tipe_penilai', 'wisatawan')->count() }} ulasan)</span>
                    </div>
                    <div class="col-6 col-md-3">
                        <span class="text-muted d-block"><i class="bi bi-patch-check-fill text-warning"></i> SKOR LOKAL (1.5x)</span>
                        <strong class="fs-6" style="color: var(--toba-primary);">{{ $destinasi->rating_lokal ?: '0.0' }}</strong>
                        <span class="text-muted">({{ $destinasi->ulasans->where('tipe_penilai', 'lokal')->count() }} warga)</span>
                    </div>
                </div>
            </div>

            <!-- Panel Aksi Kanan -->
            <div class="col-lg-4">
                <div class="p-3 rounded-4 border" style="background-color: var(--toba-neutral);">
                    <div class="d-flex justify-content-between align-items-center mb-3 small">
                        <span class="text-muted">Kisaran Harga:</span>
                        <strong class="fs-6" style="color: var(--toba-primary);">
                            Rp {{ number_format($destinasi->harga_min ?? 15000, 0, ',', '.') }} - {{ number_format($destinasi->harga_max ?? 50000, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="d-grid gap-2">
                        @auth
                            @if(auth()->user()->role === 'wisatawan')
                                <button type="button" class="btn btn-toba-primary py-2" data-bs-toggle="modal" data-bs-target="#modalSimpanRencana">
                                    <i class="bi bi-bookmark-plus-fill me-1"></i> Simpan ke Rencana Kunjungan
                                </button>
                                <a href="{{ route('ulasan.create', $destinasi) }}" class="btn btn-toba-secondary py-2">
                                    <i class="bi bi-pencil-square me-1"></i> Beri Rating &amp; Ulasan
                                </a>
                            @endif
                        @else
                            <button type="button" class="btn btn-toba-primary py-2" data-bs-toggle="modal" data-bs-target="#modalLoginPrompt">
                                <i class="bi bi-bookmark-plus-fill me-1"></i> Simpan ke Rencana Kunjungan
                            </button>
                            <button type="button" class="btn btn-toba-secondary py-2" data-bs-toggle="modal" data-bs-target="#modalLoginPrompt">
                                <i class="bi bi-pencil-square me-1"></i> Beri Rating &amp; Ulasan
                            </button>
                        @endauth

                        <a href="{{ route('destinasi.rute', $destinasi) }}" target="_blank" class="btn btn-toba-outlined py-2">
                            <i class="bi bi-sign-turn-right-fill me-1"></i> Petunjuk Rute (Maps)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Galeri Foto Destinasi -->
    @if($destinasi->fotos->count() > 0)
        <div class="row g-3 mb-4">
            @foreach($destinasi->fotos->take(3) as $foto)
                <div class="{{ $loop->first ? 'col-md-6' : 'col-md-3' }}">
                    <img src="{{ asset('storage/' . $foto->path_foto) }}" class="w-100 rounded-4 object-fit-cover border" style="height: 240px;" alt="{{ $destinasi->nama }}">
                </div>
            @endforeach
        </div>
    @endif

    <!-- Tabs Konten Utama (Screen 8) -->
    <ul class="nav nav-pills gap-2 mb-4" id="destinasiTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill px-4 fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-budaya" type="button">
                <i class="bi bi-book-half me-1"></i> Ringkasan &amp; Cerita Budaya
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-menu" type="button">
                <i class="bi bi-journal-richtext me-1"></i> Menu Kuliner ({{ $destinasi->menus->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-ulasan" type="button">
                <i class="bi bi-chat-square-quote me-1"></i> Ulasan &amp; Rating ({{ $destinasi->ulasans->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-lokasi" type="button">
                <i class="bi bi-geo-alt me-1"></i> Lokasi &amp; Peta
            </button>
        </li>
    </ul>

    <div class="tab-content">
        <!-- TAB 1: CERITA BUDAYA (Cultural Context Card) -->
        <div class="tab-pane fade show active" id="tab-budaya">
            @forelse($destinasi->ceritaBudaya as $cerita)
                <div class="card-toba p-4 mb-3">
                    <span class="badge-otentik mb-2 d-inline-block">NARASI KURASI BUDAYA • WARISAN TOBA</span>
                    <h4 class="fw-bold mb-3">{{ $cerita->judul }}</h4>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-uppercase small" style="color: var(--toba-primary);">Asal-Usul &amp; Sejarah Sajian</h6>
                            <p class="text-muted small mb-0" style="line-height: 1.7;">{{ $cerita->asal_usul }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-uppercase small" style="color: var(--toba-primary);">Makna dalam Adat Batak</h6>
                            <p class="text-muted small mb-3" style="line-height: 1.7;">{{ $cerita->makna_adat }}</p>

                            @if($cerita->istilah_lokal)
                                <div class="p-3 rounded-3 border" style="background-color: var(--toba-neutral);">
                                    <h6 class="fw-bold small mb-1"><i class="bi bi-translate me-1 text-warning"></i> Glosarium Istilah Lokal</h6>
                                    <p class="small text-dark mb-0">{{ $cerita->istilah_lokal }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="card-toba p-4 text-center text-muted">
                    Belum ada catatan cerita budaya khusus untuk destinasi ini.
                </div>
            @endforelse
        </div>

        <!-- TAB 2: MENU KULINER & HARGA -->
        <div class="tab-pane fade" id="tab-menu">
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
                                        <span class="badge bg-dark rounded-pill">Rp {{ number_format($menu->harga ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                    <p class="text-muted small mb-0">{{ $menu->deskripsi }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card-toba p-4 text-center text-muted">Daftar menu belum ditambahkan oleh pemilik usaha.</div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- TAB 3: ULASAN & DUAL RATING -->
        <div class="tab-pane fade" id="tab-ulasan">
            <div class="d-flex flex-column gap-3">
                @forelse($destinasi->ulasans as $ulasan)
                    <div class="card-toba p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold mb-0">
                                    {{ $ulasan->user->nama ?? 'Pengguna' }}
                                    @if($ulasan->tipe_penilai === 'lokal')
                                        <span class="badge-otentik ms-1">Warga Lokal (Bobot 1.5x)</span>
                                    @else
                                        <span class="badge bg-light text-dark border ms-1">Wisatawan</span>
                                    @endif
                                </h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($ulasan->created_at)->diffForHumans() }}</small>
                            </div>
                            <span class="badge bg-warning text-dark fs-6">★ {{ $ulasan->rating_umum }}.0</span>
                        </div>

                        <div class="d-flex flex-wrap gap-3 small text-muted mb-2">
                            @if($ulasan->rating_rasa) <span>Rasa: <strong>{{ $ulasan->rating_rasa }}/5</strong></span> @endif
                            @if($ulasan->rating_harga) <span>Harga: <strong>{{ $ulasan->rating_harga }}/5</strong></span> @endif
                            @if($ulasan->rating_keotentikan) <span>Keotentikan: <strong>{{ $ulasan->rating_keotentikan }}/5</strong></span> @endif
                        </div>

                        <p class="mb-2">{{ $ulasan->komentar }}</p>

                        @if($ulasan->fotos->count() > 0)
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach($ulasan->fotos as $fUlasan)
                                    <img src="{{ asset('storage/' . $fUlasan->path_foto) }}" class="rounded-3 object-fit-cover border" style="width: 90px; height: 90px;" alt="Foto Ulasan">
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="card-toba p-4 text-center text-muted">
                        Belum ada ulasan untuk destinasi ini. Jadilah yang pertama memberikan ulasan!
                    </div>
                @endforelse
            </div>
        </div>

        <!-- TAB 4: LOKASI & PETA -->
        <div class="tab-pane fade" id="tab-lokasi">
            <div class="card-toba p-4">
                <h6 class="fw-bold mb-1">Alamat Lengkap</h6>
                <p class="text-muted small mb-3">{{ $destinasi->alamat }} ({{ $destinasi->latitude }}, {{ $destinasi->longitude }})</p>
                <div id="detailMap" style="height: 320px; border-radius: 12px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL SCREEN 9: SILAKAN MASUK UNTUK MELANJUTKAN (Untuk Guest) -->
@guest
<div class="modal fade" id="modalLoginPrompt" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 p-3 text-center">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background-color: var(--toba-secondary-light); color: var(--toba-primary);">
                    <i class="bi bi-lock-fill fs-4"></i>
                </div>
                <h4 class="fw-bold">Silakan Masuk untuk Melanjutkan</h4>
                <p class="text-muted small mb-4">Aksi ini memerlukan akun penjelajah Toba Kuliner untuk memberikan ulasan berbobot budaya atau menyimpan destinasi ke rencana perjalanan Anda.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('login') }}" class="btn btn-toba-primary py-2">Masuk ke Akun Saya</a>
                    <a href="{{ route('register') }}" class="btn btn-toba-secondary py-2">Daftar Akun Baru</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endguest

<!-- MODAL SCREEN 10: SIMPAN KE RENCANA KUNJUNGAN (Untuk Wisatawan) -->
@auth
@if(auth()->user()->role === 'wisatawan')
<div class="modal fade" id="modalSimpanRencana" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Simpan ke Rencana Kunjungan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($itineraries->count() > 0)
                    <form action="" id="formTambahStop" method="POST">
                        @csrf
                        <input type="hidden" name="destinasi_id" value="{{ $destinasi->id }}">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Pilih Rencana Perjalanan</label>
                            <select id="selectItineraryId" class="form-select" required>
                                @foreach($itineraries as $itin)
                                    <option value="{{ $itin->id }}">{{ $itin->judul }} ({{ $itin->tanggal_kunjungan ?? 'Fleksibel' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Hari Ke-</label>
                                <input type="number" name="hari" value="1" min="1" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Perkiraan Waktu Tiba</label>
                                <input type="time" name="waktu_tiba" value="12:00" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Catatan Khusus / Pesanan</label>
                            <input type="text" name="catatan" class="form-control" placeholder="Contoh: Pesan mie gomak kuah andaliman">
                        </div>
                        <button type="submit" class="btn btn-toba-primary w-100 py-2">Tambahkan ke Rencana Ini</button>
                    </form>
                    <hr>
                @endif

                <form action="{{ route('itinerary.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="destinasi_id" value="{{ $destinasi->id }}">
                    <label class="form-label small fw-bold text-muted">Atau Buat Rencana Perjalanan Baru:</label>
                    <div class="input-group">
                        <input type="text" name="judul" class="form-control" placeholder="Judul rencana (mis. Jelajah Balige 2 Hari)" required>
                        <button type="submit" class="btn btn-toba-secondary">Buat &amp; Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endauth
@endsection

@push('scripts')
<script>
    // Inisialisasi peta saat tab Lokasi dibuka
    const tabLokasiBtn = document.querySelector('[data-bs-target="#tab-lokasi"]');
    let detailMapInit = false;
    tabLokasiBtn?.addEventListener('shown.bs.tab', function () {
        if (!detailMapInit) {
            const lat = {{ $destinasi->latitude }};
            const lng = {{ $destinasi->longitude }};
            const dMap = L.map('detailMap').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(dMap);
            L.marker([lat, lng]).addTo(dMap).bindPopup("{{ $destinasi->nama }}").openPopup();
            detailMapInit = true;
        }
    });

    // Dynamic action untuk form tambah stop ke itinerary terpilih
    const formStop = document.getElementById('formTambahStop');
    const selectItin = document.getElementById('selectItineraryId');
    if (formStop && selectItin) {
        const updateAction = () => {
            formStop.action = `/rencana/${selectItin.value}/stop`;
        };
        updateAction();
        selectItin.addEventListener('change', updateAction);
    }
</script>
@endpush