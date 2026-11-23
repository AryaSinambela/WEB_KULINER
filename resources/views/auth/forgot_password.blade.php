@extends('layouts.app')

@section('title', 'Lupa Kata Sandi — Toba Kuliner')

@section('content')
<div class="container py-5" style="max-width: 460px;">
    <div class="card-toba p-4 p-md-5">
        <h4 class="fw-bold mb-1">Atur Ulang Kata Sandi</h4>
        <p class="text-muted small mb-4">Masukkan alamat email akun Toba Kuliner Anda untuk melanjutkan pengaturan kata sandi baru.</p>

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-bold">Alamat Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="email@contoh.com" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <button type="submit" class="btn btn-toba-primary w-100 py-2 mb-3">Lanjutkan Atur Sandi</button>
            <div class="text-center small">
                <a href="{{ route('login') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left"></i> Kembali ke Halaman Masuk</a>
            </div>
        </form>
    </div>
</div>
@endsection
