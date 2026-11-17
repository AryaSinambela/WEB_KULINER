@extends('layouts.app')

@section('title', 'Moderasi Ulasan & Rating — Konsol Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <small class="fw-bold text-uppercase" style="color: var(--toba-primary);">PENGAWASAN KUALITAS &amp; KURASI KOMUNITAS</small>
            <h1 class="h3 fw-bold mb-0">Moderasi Ulasan &amp; Integritas Rating</h1>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-toba-secondary btn-sm">Kembali ke Dashboard</a>
    </div>

    <div class="card-toba p-4">
        <div class="table-responsive">
            <table class="table align-middle small">
                <thead class="table-light">
                    <tr>
                        <th>Destinasi</th>
                        <th>Penulis</th>
                        <th>Rating</th>
                        <th>Komentar Ulasan</th>
                        <th>Status</th>
                        <th class="text-end">Tindakan Moderasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ulasans as $u)
                        <tr>
                            <td class="fw-bold">{{ $u->destinasi->nama ?? '-' }}</td>
                            <td>{{ $u->user->nama ?? '-' }}<br><span class="badge bg-light text-dark border">{{ ucfirst($u->tipe_penilai) }}</span></td>
                            <td><span class="badge bg-warning text-dark">★ {{ $u->rating_umum }}.0</span></td>
                            <td style="max-width: 320px;">{{ $u->komentar }}</td>
                            <td>
                                <span class="badge {{ $u->status_moderasi === 'tampil' ? 'bg-success' : ($u->status_moderasi === 'ditahan' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ strtoupper($u->status_moderasi) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <form action="{{ route('admin.ulasan.moderasi', $u) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" name="status_moderasi" value="tampil" class="btn btn-outline-success btn-sm">Tayangkan</button>
                                    <button type="submit" name="status_moderasi" value="ditahan" class="btn btn-outline-warning btn-sm">Tahan</button>
                                    <button type="submit" name="status_moderasi" value="dihapus" class="btn btn-outline-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $ulasans->links() }}
    </div>
</div>
@endsection