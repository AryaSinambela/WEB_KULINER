<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CeritaBudaya extends Model {
    protected $table = 'cerita_budaya';
    protected $guarded = ['id'];
    public $timestamps = false;
    public function destinasi() { return $this->belongsTo(Destinasi::class, 'destinasi_id'); }
}
