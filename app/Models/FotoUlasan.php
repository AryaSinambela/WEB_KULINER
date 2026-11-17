<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FotoUlasan extends Model {
    protected $table = 'foto_ulasan';
    protected $guarded = ['id'];
    public $timestamps = false;
    public function ulasan() { return $this->belongsTo(Ulasan::class, 'ulasan_id'); }
}
