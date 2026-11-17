<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class StatistikKunjungan extends Model {
    protected $table = 'statistik_kunjungan';
    protected $guarded = ['id'];
    public $timestamps = false;
    public function destinasi() { return $this->belongsTo(Destinasi::class, 'destinasi_id'); }
}
