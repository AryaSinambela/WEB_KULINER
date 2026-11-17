<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Itinerary extends Model {
    protected $table = 'itinerary';
    protected $guarded = ['id'];
    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function items() { return $this->hasMany(ItineraryItem::class, 'itinerary_id'); }
}
