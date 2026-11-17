<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model
{
    protected $table = 'destinasi';
    protected $guarded = ['id'];

    protected $casts = [
        'is_otentik_batak' => 'boolean',
        'diverifikasi_pada' => 'datetime',
    ];

    public function pemilik()
    {
        return $this->belongsTo(User::class, 'pemilik_id');
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriMakanan::class, 'kategori_id');
    }

    public function fotos()
    {
        return $this->hasMany(FotoDestinasi::class, 'destinasi_id');
    }

    public function fotoUtama()
    {
        return $this->hasOne(FotoDestinasi::class, 'destinasi_id')->where('is_utama', true);
    }

    public function menus()
    {
        return $this->hasMany(MenuDestinasi::class, 'destinasi_id');
    }

    public function ceritaBudaya()
    {
        return $this->hasMany(CeritaBudaya::class, 'destinasi_id');
    }

    public function ulasans()
    {
        return $this->hasMany(Ulasan::class, 'destinasi_id')->where('status_moderasi', 'tampil');
    }

    public function pengajuanOtentik()
    {
        return $this->hasMany(PengajuanOtentik::class, 'destinasi_id');
    }

    public function statistik()
    {
        return $this->hasMany(StatistikKunjungan::class, 'destinasi_id');
    }

    // Accessor Dual Rating System (Wisatawan vs Lokal)
    public function getRatingWisatawanAttribute()
    {
        return round($this->ulasans()->where('tipe_penilai', 'wisatawan')->avg('rating_umum') ?? 0, 1);
    }

    public function getRatingLokalAttribute()
    {
        return round($this->ulasans()->where('tipe_penilai', 'lokal')->avg('rating_umum') ?? 0, 1);
    }
}