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
                    <span class="badge bg-danger text-white"><i class="bi bi-shop me-1"></i> TOKO / RUMAH MAKAN RESMI</span>
                    @if($destinasi->is_otentik_batak)
                        <span class="badge-otentik"><i class="bi bi-award-fill text-warning me-1"></i> Otentik Batak Terverifikasi</span>
                    @endif
                    <span class="badge bg-light text-dark border">{{ $destinasi->kategori->nama ?? 'Kuliner Toba' }}</span>
                </div>

                <h1 class="h2 fw-bold mb-1">{{ $destinasi->nama }}</h1>
                <p class="small text-muted mb-2">
                    <i class="bi bi-person-badge-fill text-danger me-1"></i> Dikelola oleh: <strong>{{ $destinasi->pemilik->nama ?? 'Keluarga Peracik Tradisi Toba' }}</strong>
                    • <i class="bi bi-geo-alt-fill text-danger ms-1"></i> {{ $destinasi->alamat }}
                </p>
                <p class="text-muted mb-3">{{ $destinasi->deskripsi }}</p>

                <div class="row g-3 pt-3 border-top small" style="border-color: var(--toba-border) !important;">
                    <div class="col-6 col-md-3">
                        <span class="text-muted d-block"><i class="bi bi-geo-alt-fill text-danger"></i> WILAYAH</span>
                        <strong class="text-dark">{{ $destinasi->wilayah->nama_kecamatan ?? '' }}, {{ $destinasi->wilayah->kabupaten ?? '' }}</strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <span class="text-muted d-block"><i class="bi bi-clock-fill text-success"></i> JAM BUKA TOKO</span>
                        <strong class="text-dark">
                            {{ $destinasi->jam_buka ? substr($destinasi->jam_buka, 0, 5) : '08:00' }} - {{ $destinasi->jam_tutup ? substr($destinasi->jam_tutup, 0, 5) : '20:00' }} WIB
                        </strong>
                    </div>
                    <div class="col-6 col-md-3">
                        <span class="text-muted d-block"><i class="bi bi-star-fill text-warning"></i> RATING WISATAWAN</span>
                        <strong class="text-dark fs-6">{{ $destinasi->rating_wisatawan ?: '4.7' }}</strong>
                        <span class="text-muted">({{ $destinasi->ulasans->where('tipe_penilai', 'wisatawan')->count() }} ulasan)</span>
                    </div>
                    <div class="col-6 col-md-3">
                        <span class="text-muted d-block"><i class="bi bi-patch-check-fill text-warning"></i> SKOR LOKAL (1.5x)</span>
                        <strong class="fs-6" style="color: var(--toba-primary);">{{ $destinasi->rating_lokal ?: '4.9' }}</strong>
                        <span class="text-muted">({{ $destinasi->ulasans->where('tipe_penilai', 'lokal')->count() }} warga)</span>
                    </div>
                </div>
            </div>

            <!-- Panel Aksi Kanan -->
            <div class="col-lg-4">
                <div class="p-3 rounded-4 border" style="background-color: var(--toba-neutral);">
                    <div class="d-flex justify-content-between align-items-center mb-3 small">
                        <span class="text-muted">Kisaran Harga Menu:</span>
                        <strong class="fs-6" style="color: var(--toba-primary);">
                            Rp {{ number_format($destinasi->harga_min ?? 15000, 0, ',', '.') }} - {{ number_format($destinasi->harga_max ?? 50000, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="d-grid gap-2">
                        @auth
                            <button type="button" class="btn btn-toba-primary py-2" data-bs-toggle="modal" data-bs-target="#modalSimpanRencana">
                                <i class="bi bi-bookmark-plus-fill me-1"></i> Simpan Toko ke Rencana Kunjungan
                            </button>
                            <a href="{{ route('ulasan.create', $destinasi) }}" class="btn btn-toba-secondary py-2">
                                <i class="bi bi-pencil-square me-1"></i> Beri Rating &amp; Ulasan Toko
                            </a>
                        @else
                            <button type="button" class="btn btn-toba-primary py-2" data-bs-toggle="modal" data-bs-target="#modalLoginPrompt">
                                <i class="bi bi-bookmark-plus-fill me-1"></i> Simpan Toko ke Rencana Kunjungan
                            </button>
                            <button type="button" class="btn btn-toba-secondary py-2" data-bs-toggle="modal" data-bs-target="#modalLoginPrompt">
                                <i class="bi bi-pencil-square me-1"></i> Beri Rating &amp; Ulasan Toko
                            </button>
                        @endauth

                        <a href="{{ route('destinasi.rute', $destinasi) }}" target="_blank" class="btn btn-toba-outlined py-2">
                            <i class="bi bi-sign-turn-right-fill me-1"></i> Petunjuk Rute ke Toko (Maps)
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
                <div class="{{ $loop->first ? ($destinasi->fotos->count() == 1 ? 'col-12' : 'col-md-6') : 'col-md-3' }}">
                    <img src="{{ $foto->url_foto }}" class="w-100 rounded-4 object-fit-cover border" style="height: 260px;" alt="{{ $destinasi->nama }}">
                </div>
            @endforeach
        </div>
    @endif

    <!-- Tabs Konten Utama (Screen 8) -->
    <ul class="nav nav-pills gap-2 mb-4" id="destinasiTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill px-4 fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-menu" type="button">
                <i class="bi bi-journal-richtext me-1"></i> Daftar Menu &amp; Cerita per Hidangan ({{ $destinasi->menus->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-budaya" type="button">
                <i class="bi bi-book-half me-1"></i> Arsip Lengkap Cerita Budaya ({{ $destinasi->ceritaBudaya->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-ulasan" type="button">
                <i class="bi bi-chat-square-quote me-1"></i> Ulasan &amp; Rating ({{ $destinasi->ulasans->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-lokasi" type="button">
                <i class="bi bi-geo-alt me-1"></i> Lokasi &amp; Navigasi Peta
            </button>
        </li>
    </ul>

    <div class="tab-content">
        <!-- TAB 1: MENU KULINER, HARGA & CERITA BUDAYA PER MENU -->
        <div class="tab-pane fade show active" id="tab-menu">
            <div class="row g-4">
                @forelse($destinasi->menus as $idx => $menu)
                    @php
                        // Cocokkan cerita budaya untuk menu ini berdasarkan nama menu atau urutan indeks
                        $ceritaMenu = $destinasi->ceritaBudaya->first(function($c) use ($menu) {
                            return str_contains(strtolower($c->judul), strtolower($menu->nama_menu));
                        }) ?? $destinasi->ceritaBudaya->values()->get($idx) ?? $destinasi->ceritaBudaya->first();
                    @endphp
                    <div class="col-md-6">
                        <div class="card-toba h-100 overflow-hidden d-flex flex-column">
                            <img src="{{ $menu->url_foto }}" class="w-100 object-fit-cover border-bottom" style="height: 190px;" alt="{{ $menu->nama_menu }}">
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                    <div>
                                        <span class="badge bg-light text-danger border mb-1" style="font-size: 10px;">
                                            <i class="bi bi-shop me-1"></i> {{ $destinasi->nama }}
                                        </span>
                                        <h5 class="fw-bold mb-0">{{ $menu->nama_menu }}</h5>
                                    </div>
                                    <span class="badge bg-dark rounded-pill fs-6 px-3 py-2">Rp {{ number_format($menu->harga ?? 0, 0, ',', '.') }}</span>
                                </div>

                                <p class="text-muted small mb-3">{{ $menu->deskripsi }}</p>

                                @if($ceritaMenu)
                                    <div class="p-3 rounded-4 border mt-auto" style="background-color: var(--toba-neutral);">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge-otentik" style="font-size: 10px;">
                                                <i class="bi bi-book-half text-warning me-1"></i> CERITA &amp; FILOSOFI MENU INI
                                            </span>
                                        </div>
                                        <h6 class="fw-bold small mb-2" style="color: var(--toba-primary);">{{ $ceritaMenu->judul }}</h6>
                                        <div class="small text-muted mb-2" style="line-height: 1.6;">
                                            <strong class="text-dark d-block">Asal-Usul Sajian:</strong>
                                            {{ $ceritaMenu->asal_usul }}
                                        </div>
                                        <div class="small text-muted mb-2" style="line-height: 1.6;">
                                            <strong class="text-dark d-block">Makna dalam Adat Batak:</strong>
                                            {{ $ceritaMenu->makna_adat }}
                                        </div>
                                        @if($ceritaMenu->istilah_lokal)
                                            <div class="p-2 rounded-3 bg-white border small mt-2">
                                                <i class="bi bi-translate text-warning me-1"></i> <strong>Istilah Lokal:</strong> {{ $ceritaMenu->istilah_lokal }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card-toba p-4 text-center text-muted">Daftar menu belum ditambahkan oleh pemilik toko.</div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- TAB 2: ARSIP LENGKAP CERITA BUDAYA -->
        <div class="tab-pane fade" id="tab-budaya">
            @forelse($destinasi->ceritaBudaya as $cerita)
                <div class="card-toba p-4 mb-3">
                    <span class="badge-otentik mb-2 d-inline-block">NARASI KURASI BUDAYA • {{ strtoupper($destinasi->nama) }}</span>
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

        <!-- TAB 4: LOKASI & NAVIGASI PETA (A -> B GOOGLE MAPS STYLE) -->
        <div class="tab-pane fade" id="tab-lokasi">
            <div class="card-toba p-4">
                <div class="row g-3">
                    <!-- Panel Kontrol Titik Awal (A) & Tujuan Toko (B) -->
                    <div class="col-lg-4">
                        <div class="p-3 rounded-4 border h-100" style="background-color: var(--toba-neutral);">
                            <h6 class="fw-bold mb-2" style="color: var(--toba-primary);">
                                <i class="bi bi-sign-turn-right-fill me-1"></i> Navigasi Rute ke Toko Ini
                            </h6>

                            <!-- Titik Awal (A) -->
                            <div class="mb-2">
                                <label class="form-label small fw-bold text-primary mb-1">
                                    <span class="badge bg-primary rounded-circle me-1">A</span> Titik Awal Keberangkatan *
                                </label>
                                <div class="input-group input-group-sm mb-1">
                                    <input type="text" id="detailOriginInput" class="form-control" placeholder="Ketik kota/desa awal (mis. Parapat)...">
                                    <button type="button" id="btnDetailSearchOrigin" class="btn btn-outline-primary" title="Cari Lokasi Awal">
                                        <i class="bi bi-search"></i>
                                    </button>
                                    <button type="button" id="btnDetailRouteGps" class="btn btn-primary" title="Gunakan GPS Saya">
                                        <i class="bi bi-crosshair"></i> GPS
                                    </button>
                                </div>
                                <select id="detailQuickOrigin" class="form-select form-select-sm bg-white" style="font-size: 11.5px;">
                                    <option value="">-- Atau pilih titik awal cepat di Toba --</option>
                                    <option value="2.3338,99.0664,Pusat Kota Balige">Pusat Kota Balige (Toba)</option>
                                    <option value="2.6632,98.9341,Pantai Bebas Parapat">Pelabuhan / Pantai Bebas Parapat</option>
                                    <option value="2.6085,98.6942,Pusat Kota Pangururan">Pusat Kota Pangururan (Samosir)</option>
                                    <option value="2.6718,98.8574,Dermaga Tuktuk Siadong">Dermaga Wisata Tuktuk Siadong</option>
                                    <option value="2.2571,98.9912,Bandara Silangit">Bandara Internasional Silangit</option>
                                    <option value="2.0235,98.9612,Pusat Kota Tarutung">Pusat Kota Tarutung (Taput)</option>
                                </select>
                                <small class="text-muted d-block mt-1" style="font-size: 11px;">
                                    *Anda juga bisa mengklik langsung di peta atau menggeser pin <strong>A</strong>.
                                </small>
                            </div>

                            <!-- Titik Tujuan (B - Toko Ini) -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-danger mb-1">
                                    <span class="badge bg-danger rounded-circle me-1">B</span> Titik Tujuan (Toko Kuliner)
                                </label>
                                <input type="text" class="form-control form-control-sm bg-white fw-semibold" value="{{ $destinasi->nama }} ({{ $destinasi->alamat }})" readonly>
                            </div>

                            <div class="d-grid gap-2 mb-3">
                                <button type="button" id="btnDetailCalcRoute" class="btn btn-toba-primary btn-sm py-2">
                                    <i class="bi bi-sign-turn-right-fill me-1"></i> Tampilkan Rute Jalan Raya
                                </button>
                                <a href="{{ route('destinasi.rute', $destinasi) }}" id="detailExternalGmaps" target="_blank" class="btn btn-toba-outlined btn-sm py-1">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka di Aplikasi Google Maps
                                </a>
                            </div>

                            <!-- Ringkasan Jarak, Waktu, & Petunjuk Jalan -->
                            <div id="detailRouteSummary" class="d-none">
                                <div class="row g-2 text-center mb-2">
                                    <div class="col-6">
                                        <div class="p-2 rounded-3 bg-white border">
                                            <small class="text-muted d-block" style="font-size: 10px;">JARAK TEMPUH</small>
                                            <strong class="text-danger" id="detailRouteDist">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 rounded-3 bg-white border">
                                            <small class="text-muted d-block" style="font-size: 10px;">WAKTU TEMPUH</small>
                                            <strong class="text-success" id="detailRouteTime">-</strong>
                                        </div>
                                    </div>
                                </div>
                                <div id="detailRouteSteps" class="rounded-3 border bg-white p-2 small overflow-auto" style="max-height: 150px; font-size: 11.5px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Kanvas Peta Rute Interaktif -->
                    <div class="col-lg-8">
                        <div id="detailMap" style="height: 440px; border-radius: 14px;" class="border shadow-sm"></div>
                    </div>
                </div>
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

<!-- MODAL SCREEN 10: SIMPAN KE RENCANA KUNJUNGAN (Untuk Semua Pengguna yang Login) -->
@auth
<div class="modal fade" id="modalSimpanRencana" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Simpan Toko ke Rencana Kunjungan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($itineraries->count() > 0)
                    <form action="{{ route('itinerary.stop', $itineraries->first()->id) }}" id="formTambahStop" method="POST">
                        @csrf
                        <input type="hidden" name="destinasi_id" value="{{ $destinasi->id }}">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Pilih Rencana Perjalanan Anda</label>
                            <select id="selectItineraryId" name="itinerary_id" class="form-select" required>
                                @foreach($itineraries as $itin)
                                    <option value="{{ $itin->id }}" data-action-url="{{ route('itinerary.stop', $itin->id) }}">
                                        {{ $itin->judul }} ({{ $itin->tanggal_kunjungan ?? 'Fleksibel' }})
                                    </option>
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
                        <button type="submit" class="btn btn-toba-primary w-100 py-2">
                            <i class="bi bi-check2-circle me-1"></i> Tambahkan Toko ke Rencana Ini
                        </button>
                    </form>
                    <hr>
                @endif

                <form action="{{ route('itinerary.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="destinasi_id" value="{{ $destinasi->id }}">
                    <label class="form-label small fw-bold text-muted">Atau Buat Rencana Perjalanan Baru &amp; Langsung Simpan Toko Ini:</label>
                    <div class="row g-2 mb-2">
                        <div class="col-12">
                            <input type="text" name="judul" class="form-control" placeholder="Judul rencana baru (mis. Jelajah Balige & Samosir)" required>
                        </div>
                        <div class="col-6">
                            <input type="date" name="tanggal_kunjungan" value="{{ date('Y-m-d') }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <select name="moda" class="form-select form-select-sm">
                                <option value="Mobil">Mobil</option>
                                <option value="Motor">Motor</option>
                                <option value="Kapal Feri & Darat">Kapal Feri &amp; Darat</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-toba-secondary w-100 py-2">
                        <i class="bi bi-plus-circle me-1"></i> Buat Rencana Baru &amp; Simpan Toko
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endauth
@endsection

@push('scripts')
<script>
    const tabLokasiBtn = document.querySelector('[data-bs-target="#tab-lokasi"]');
    let detailMapInit = false;
    let dMap = null;
    let dRouteLayer = null;
    let dOriginMarker = null;
    let dOriginCoords = null;

    const destLat = {{ (float) $destinasi->latitude }};
    const destLng = {{ (float) $destinasi->longitude }};

    function createBadgePin(letter, bgColor) {
        return L.divIcon({
            className: '',
            html: `<div style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;background:${bgColor};color:#fff;font-weight:800;font-size:14px;border:3px solid #fff;box-shadow:0 4px 10px rgba(0,0,0,0.4);">${letter}</div>`,
            iconSize: [34, 34],
            iconAnchor: [17, 17]
        });
    }

    function drawOsrmDetailRoute(startLat, startLng, originLabel = null) {
        if (!dMap) return;
        dOriginCoords = { lat: startLat, lng: startLng };

        if (dOriginMarker) dMap.removeLayer(dOriginMarker);
        dOriginMarker = L.marker([startLat, startLng], {
            icon: createBadgePin('A', '#0d6efd'),
            draggable: true
        }).addTo(dMap);

        dOriginMarker.on('dragend', function (e) {
            const p = e.target.getLatLng();
            drawOsrmDetailRoute(p.lat, p.lng);
        });

        if (originLabel) {
            document.getElementById('detailOriginInput').value = originLabel;
            dOriginMarker.bindPopup(`<strong>Titik Awal (A): ${originLabel}</strong><br><small>Geser pin A untuk mengubah titik awal</small>`);
        } else {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${startLat}&lon=${startLng}`)
                .then(r => r.json())
                .then(d => {
                    const addr = d.display_name ? d.display_name.split(',').slice(0, 3).join(',') : `${startLat.toFixed(4)}, ${startLng.toFixed(4)}`;
                    document.getElementById('detailOriginInput').value = addr;
                    dOriginMarker.bindPopup(`<strong>Titik Awal (A): ${addr}</strong>`);
                }).catch(() => {});
        }

        document.getElementById('detailExternalGmaps').href = `https://www.google.com/maps/dir/?api=1&origin=${startLat},${startLng}&destination=${destLat},${destLng}`;

        fetch(`https://router.project-osrm.org/route/v1/driving/${startLng},${startLat};${destLng},${destLat}?overview=full&geometries=geojson&steps=true`)
            .then(r => r.json())
            .then(res => {
                dRouteLayer.clearLayers();
                if (res.routes && res.routes.length > 0) {
                    const route = res.routes[0];
                    L.geoJSON(route.geometry, { style: { color: '#ffffff', weight: 8, opacity: 0.9 } }).addTo(dRouteLayer);
                    const line = L.geoJSON(route.geometry, {
                        style: { color: '#0d6efd', weight: 5, opacity: 0.95 }
                    }).addTo(dRouteLayer);
                    dMap.fitBounds(line.getBounds(), { padding: [45, 45] });

                    document.getElementById('detailRouteDist').textContent = (route.distance / 1000).toFixed(1) + ' km';
                    document.getElementById('detailRouteTime').textContent = Math.max(1, Math.round(route.duration / 60)) + ' menit';
                    document.getElementById('detailRouteSummary').classList.remove('d-none');

                    const steps = route.legs?.[0]?.steps || [];
                    const stepsContainer = document.getElementById('detailRouteSteps');
                    if (steps.length > 0) {
                        stepsContainer.innerHTML = `<strong class="d-block mb-1 text-dark">Panduan Arah Jalan:</strong>` +
                            steps.map((s, i) => {
                                const dText = s.distance >= 1000 ? (s.distance / 1000).toFixed(1) + ' km' : Math.round(s.distance) + ' m';
                                const road = s.name ? ` ke <strong>${s.name}</strong>` : '';
                                return `<div class="py-1 border-bottom">${i + 1}. Ikuti jalur${road} <span class="text-muted">(${dText})</span></div>`;
                            }).join('');
                    }
                }
            });
    }

    tabLokasiBtn?.addEventListener('shown.bs.tab', function () {
        if (!detailMapInit) {
            dMap = L.map('detailMap').setView([destLat, destLng], 14);
            const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(dMap);
            const satHybrid = L.layerGroup([
                L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'),
                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png')
            ]);
            const topo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png');

            L.control.layers({
                "🗺️ Peta Jalan (OSM)": osm,
                "🛰️ Satelit + Jalan (Hybrid)": satHybrid,
                "⛰️ Topografi Kaldera": topo
            }).addTo(dMap);

            dRouteLayer = L.layerGroup().addTo(dMap);
            L.marker([destLat, destLng], { icon: createBadgePin('B', '#dc3545') })
                .addTo(dMap)
                .bindPopup("<strong>Tujuan (B): {{ addslashes($destinasi->nama) }}</strong><br><small>Klik titik mana saja di peta untuk menetapkan Titik Awal (A)</small>")
                .openPopup();

            dMap.on('click', function (e) {
                drawOsrmDetailRoute(e.latlng.lat, e.latlng.lng);
            });

            detailMapInit = true;
        }
    });

    document.getElementById('detailQuickOrigin')?.addEventListener('change', function () {
        if (!this.value) return;
        const [lat, lng, label] = this.value.split(',');
        drawOsrmDetailRoute(parseFloat(lat), parseFloat(lng), label);
    });

    function searchDetailOrigin() {
        const q = document.getElementById('detailOriginInput').value.trim();
        if (!q) {
            if (dOriginCoords) drawOsrmDetailRoute(dOriginCoords.lat, dOriginCoords.lng);
            else drawOsrmDetailRoute(2.3338, 99.0664, 'Pusat Kota Balige');
            return;
        }
        fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(q + ' Sumatera Utara')}&limit=1`)
            .then(r => r.json())
            .then(res => {
                if (res.length > 0) {
                    const shortName = res[0].display_name.split(',').slice(0, 2).join(',');
                    drawOsrmDetailRoute(parseFloat(res[0].lat), parseFloat(res[0].lon), shortName);
                } else {
                    alert('Lokasi titik awal tidak ditemukan. Silakan pilih dari dropdown atau klik langsung di peta.');
                }
            });
    }

    document.getElementById('btnDetailSearchOrigin')?.addEventListener('click', searchDetailOrigin);
    document.getElementById('btnDetailCalcRoute')?.addEventListener('click', searchDetailOrigin);

    document.getElementById('btnDetailRouteGps')?.addEventListener('click', function () {
        if (!navigator.geolocation) {
            alert('Geolokasi tidak didukung browser Anda. Silakan klik titik awal langsung pada peta.');
            return;
        }
        navigator.geolocation.getCurrentPosition(
            pos => drawOsrmDetailRoute(pos.coords.latitude, pos.coords.longitude, 'Lokasi GPS Saya'),
            () => drawOsrmDetailRoute(2.3338, 99.0664, 'Pusat Kota Balige')
        );
    });

    // Dynamic action untuk form tambah stop ke itinerary terpilih menggunakan data-action-url
    const formStop = document.getElementById('formTambahStop');
    const selectItin = document.getElementById('selectItineraryId');
    if (formStop && selectItin) {
        const updateAction = () => {
            const selectedOpt = selectItin.options[selectItin.selectedIndex];
            if (selectedOpt && selectedOpt.dataset.actionUrl) {
                formStop.action = selectedOpt.dataset.actionUrl;
            }
        };
        updateAction();
        selectItin.addEventListener('change', updateAction);
    }
</script>
@endpush