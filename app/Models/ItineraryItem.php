<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ItineraryItem extends Model {
    protected $table = 'itinerary_item';
    protected $guarded = ['id'];
    public $timestamps = false;
    public function itinerary() { return $this->belongsTo(Itinerary::class, 'itinerary_id'); }
    public function destinasi() { return $this->belongsTo(Destinasi::class, 'destinasi_id'); }
    public function wisataAlam() { return $this->belongsTo(WisataAlam::class, 'wisata_alam_id'); }
}
