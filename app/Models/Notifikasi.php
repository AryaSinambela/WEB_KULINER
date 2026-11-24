<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $guarded = ['id'];

    protected $casts = [
        'dibaca_pada' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Accessor untuk mengecek apakah notifikasi sudah dibaca.
     */
    public function getDibacaAttribute(): bool
    {
        return !is_null($this->dibaca_pada);
    }

    /**
     * Scope untuk notifikasi yang belum dibaca.
     */
    public function scopeBelumDibaca($query)
    {
        return $query->whereNull('dibaca_pada');
    }
}
