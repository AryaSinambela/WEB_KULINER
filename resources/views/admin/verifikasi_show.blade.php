@extends('layouts.app')

@section('title', 'Detail Verifikasi Toko: ' . $destinasi->nama)

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <a href="{{ route('admin.verifikasi.index') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left"></i> Kembali ke Antrean Verifikasi</a>
            <h1 class="h3 fw-bold mb-0 mt-1">Tinjauan Kurasi Toko: {{ $destinasi->nama }}</h1>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('destinasi.show', $destinasi) }}" target="_blank" class="btn btn-toba-secondary btn-sm px-3">
                <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Halaman Publik
            </a>
            @if($destinasi->status_verifikasi !== 'disetujui')
                <form action="{{ route('admin.verifikasi.setujui', $destinasi) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm px-3"><i class="bi bi-check-circle me-1"></i> Setujui &amp; Publikasikan</button>
                </form>
            @endif
            @if(!$destinasi->is_otentik_batak)
                <form action="{{ route('admin.verifikasi.label', $destinasi) }}" method="POST">
                    @csrf
                    <input type="hidden" name="aksi" value="disetujui">
                    <button type="submit" class="btn btn-warning btn-sm px-3 fw-bold"><i class="bi bi-award-fill me-1"></i> Terbitkan Label Otentik Batak</button>
                </form>
            @else
                <form action="{{ route('admin.verifikasi.label', $destinasi) }}" method="POST" onsubmit="return confirm('Cabut Label Otentik Batak?')">
                    @csrf
                    <input type="hidden" name="aksi" value="cabut">
                    <button type="submit" class="btn btn-outline-danger btn-sm px-3">Cabut Label Otentik</button>
                </form>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card-toba p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Data Identitas Toko &amp; Pemilik</h5>
                    <span class="badge {{ $destinasi->status_verifikasi === 'disetujui' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ strtoupper($destinasi->status_verifikasi) }}
                    </span>
                </div>
                <div class="row g-3 small">
                    <div class="col-6">
                        <span class="text-muted d-block">Nama Toko:</span>
                        <strong>{{ $destinasi->nama }}</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-muted d-block">Pemilik Usaha:</span>
                        <strong>{{ $destinasi->pemilik->nama ?? '-' }}</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-muted d-block">Wilayah:</span>
                        <strong>{{ $destinasi->wilayah->nama_kecamatan ?? '' }}, Kab. {{ $destinasi->wilayah->kabupaten ?? '' }}</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-muted d-block">Kategori &amp; Kisaran Harga:</span>
                        <strong>{{ $destinasi->kategori->nama ?? '-' }} (Rp {{ number_format($destinasi->harga_min ?? 15000, 0, ',', '.') }} - {{ number_format($destinasi->harga_max ?? 50000, 0, ',', '.') }})</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-muted d-block">Alamat Lengkap:</span>
                        <strong>{{ $destinasi->alamat }}</strong>
                    </div>
                    <div class="col-6">
                        <span class="text-muted d-block">Koordinat GPS &amp; Jam Operasional:</span>
                        <strong>{{ $destinasi->latitude }}, {{ $destinasi->longitude }} ({{ substr($destinasi->jam_buka ?? '08:00', 0, 5) }} - {{ substr($destinasi->jam_tutup ?? '20:00', 0, 5) }})</strong>
                    </div>
                </div>
                <hr>
                <p class="small text-muted mb-0">{{ $destinasi->deskripsi }}</p>
            </div>

            <div class="card-toba p-4 mb-4">
                <h5 class="fw-bold mb-3">Daftar Menu &amp; Cerita Budaya ({{ $destinasi->menus->count() }} Menu)</h5>
                @forelse($destinasi->menus as $idx => $m)
                    @php
                        $ceritaMenu = $destinasi->ceritaBudaya->first(function($c) use ($m) {
                            return str_contains(strtolower($c->judul), strtolower($m->nama_menu));
                        }) ?? $destinasi->ceritaBudaya->values()->get($idx);
                    @endphp
                    <div class="p-3 rounded-3 border mb-3 bg-light">
                        <div class="d-flex justify-content-between mb-1">
                            <strong class="small">{{ $m->nama_menu }}</strong>
                            <span class="badge bg-dark">Rp {{ number_format($m->harga, 0, ',', '.') }}</span>
                        </div>
                        <small class="text-muted d-block mb-2">{{ $m->deskripsi }}</small>
                        @if($ceritaMenu)
                            <div class="p-2 rounded-3 bg-white border small">
                                <span class="badge-otentik mb-1 d-inline-block" style="font-size: 9.5px;">NARASI BUDAYA MENU</span>
                                <strong class="d-block text-dark">{{ $ceritaMenu->judul }}</strong>
                                <span class="text-muted d-block">{{ $ceritaMenu->asal_usul }}</span>
                                @if($ceritaMenu->istilah_lokal)
                                    <span class="text-dark d-block mt-1"><em>Istilah: {{ $ceritaMenu->istilah_lokal }}</em></span>
                                @endif
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-muted small mb-0">Belum ada menu yang didaftarkan.</p>
                @endforelse
            </div>
        </div>

        <div class="col-lg-5">
            <!-- Peta Titik GPS Toko -->
            <div class="card-toba p-3 mb-4">
                <h6 class="fw-bold mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Verifikasi Titik Koordinat Peta</h6>
                <div id="verifikasiMap" style="height: 250px; border-radius: 12px;" class="border"></div>
            </div>

            <!-- Form Catatan Revisi / Penolakan -->
            <div class="card-toba p-4">
                <h5 class="fw-bold mb-2">Kirim Catatan Revisi / Penolakan</h5>
                <p class="text-muted small mb-3">Jika data lokasi GPS, menu, atau narasi budaya belum sesuai, kirimkan catatan perbaikan kepada pemilik toko.</p>
                <form action="{{ route('admin.verifikasi.revisi', $destinasi) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea name="catatan_verifikasi" rows="3" class="form-control" placeholder="Tuliskan catatan perbaikan untuk pemilik toko..." required>{{ $destinasi->catatan_verifikasi }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-danger w-100">Kirim Catatan Revisi ke Mitra</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lat = {{ (float) $destinasi->latitude }};
    const lng = {{ (float) $destinasi->longitude }};
    const vMap = L.map('verifikasiMap').setView([lat, lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(vMap);
    L.marker([lat, lng]).addTo(vMap).bindPopup("<strong>{{ addslashes($destinasi->nama) }}</strong>").openPopup();
});
</script>
@endpush
