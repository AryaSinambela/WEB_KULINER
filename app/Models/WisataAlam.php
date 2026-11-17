<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WisataAlam extends Model {
    protected $table = 'wisata_alam';
    protected $guarded = ['id'];
    public $timestamps = false;
    public function wilayah() { return $this->belongsTo(Wilayah::class, 'wilayah_id'); }
}
