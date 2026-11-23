@extends('layouts.app')

@section('title', 'Masuk Konsol Kurator & Administrator — Toba Kuliner')

@section('content')
<div class="container py-5" style="max-width: 460px;">
    <div class="card-toba p-4 p-md-5">
        <span class="badge bg-dark mb-2 d-inline-block"><i class="bi bi-shield-lock-fill text-warning me-1"></i> DEWAN KURATOR &amp; ADMIN</span>
        <h4 class="fw-bold mb-1">Konsol Administrator</h4>
        <p class="text-muted small mb-4">Akses khusus verifikasi toko, akreditasi Label Otentik Batak, dan pemantauan peta sebaran Kaldera Toba.</p>

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-bold">Email Kurator / Admin *</label>
                <input type="email" name="email" value="{{ old('email', 'admin@tobakuliner.id') }}" class="form-control" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">Kata Sandi *</label>
                <input type="password" name="password" value="password123" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-toba-primary w-100 py-2">Masuk Konsol Admin</button>
        </form>
    </div>
</div>
@endsection
