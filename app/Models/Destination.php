<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id', 'name', 'slug', 'description', 'cultural_story',
        'regency', 'address', 'latitude', 'longitude', 
        'is_authentic_batak', 'image_cover', 'opening_time', 'closing_time'
    ];

    // Relasi ke User (Owner)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Relasi ke Review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Hitung rerata rating dari wisatawan
    public function getTouristRatingAttribute()
    {
        return round($this->reviews()->where('reviewer_type', 'tourist')->avg('taste_rating') ?? 0, 1);
    }

    // Hitung rerata rating dari warga lokal
    public function getLocalRatingAttribute()
    {
        return round($this->reviews()->where('reviewer_type', 'local')->avg('taste_rating') ?? 0, 1);
    }
}