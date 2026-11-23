@extends('layouts.app')

@section('title', 'Buat Kata Sandi Baru — Toba Kuliner')

@section('content')
<div class="container py-5" style="max-width: 460px;">
    <div class="card-toba p-4 p-md-5">
        <h4 class="fw-bold mb-1">Buat Kata Sandi Baru</h4>
        <p class="text-muted small mb-4">Silakan masukkan kata sandi baru untuk akun Anda.</p>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-3">
                <label class="form-label small fw-bold">Alamat Email *</label>
                <input type="email" name="email" value="{{ old('email', $email) }}" class="form-control" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Kata Sandi Baru (Min. 8 Karakter) *</label>
                <input type="password" name="password" class="form-control" required minlength="8">
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">Konfirmasi Kata Sandi Baru *</label>
                <input type="password" name="password_confirmation" class="form-control" required minlength="8">
            </div>
            <button type="submit" class="btn btn-toba-primary w-100 py-2">Simpan Kata Sandi Baru</button>
        </form>
    </div>
</div>
@endsection
