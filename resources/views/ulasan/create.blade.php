@extends('layouts.app')

@section('title', 'Beri Ulasan — ' . $destinasi->nama)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card-toba p-4">
                <small class="fw-bold text-uppercase" style="color: var(--toba-primary);">KONTRIBUSI GASTRONOMI TOBA</small>
                <h2 class="fw-bold mb-1">Formulir Kurasi &amp; Ulasan Rasa</h2>
                <p class="text-muted small mb-4">Destinasi: <strong>{{ $destinasi->nama }}</strong> • Status Penilai Anda: 
                    <span class="badge-otentik">{{ auth()->user()->tipe_penilai === 'lokal' ? 'Warga Lokal (Bobot 1.5x)' : 'Wisatawan (Bobot 1.0x)' }}</span>
                </p>

                <form action="{{ isset($ulasan) ? route('ulasan.update', $ulasan) : route('ulasan.store', $destinasi) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($ulasan)) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label fw-bold">1. Penilaian Bintang Keseluruhan (1 - 5) *</label>
                        <select name="rating_umum" class="form-select" required>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ (old('rating_umum', $ulasan->rating_umum ?? 5) == $i) ? 'selected' : '' }}>
                                    {{ str_repeat('★', $i) }} ({{ $i }}/5)
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Keaslian Bumbu &amp; Rasa</label>
                            <select name="rating_rasa" class="form-select form-select-sm">
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ (old('rating_rasa', $ulasan->rating_rasa ?? 5) == $i) ? 'selected' : '' }}>{{ $i }} Bintang</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Kewajaran Harga (Value)</label>
                            <select name="rating_harga" class="form-select form-select-sm">
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ (old('rating_harga', $ulasan->rating_harga ?? 5) == $i) ? 'selected' : '' }}>{{ $i }} Bintang</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Keotentikan Tradisi</label>
                            <select name="rating_keotentikan" class="form-select form-select-sm">
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ (old('rating_keotentikan', $ulasan->rating_keotentikan ?? 5) == $i) ? 'selected' : '' }}>{{ $i }} Bintang</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">2. Esai Pengalaman &amp; Catatan Rasa *</label>
                        <textarea name="komentar" rows="4" class="form-control" placeholder="Ceritakan aroma andaliman, tekstur hidangan, dan suasana kedai..." required>{{ old('komentar', $ulasan->komentar ?? '') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">3. Unggah Foto Hidangan &amp; Kedai (Opsional)</label>
                        <input type="file" name="fotos[]" class="form-control" multiple accept="image/*">
                        <small class="text-muted">Dapat memilih lebih dari satu foto (maks. 2MB per foto).</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('destinasi.show', $destinasi) }}" class="btn btn-toba-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-toba-primary px-4">Kirim Ulasan Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection