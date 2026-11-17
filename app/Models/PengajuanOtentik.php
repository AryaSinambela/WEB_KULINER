<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PengajuanOtentik extends Model {
    protected $table = 'pengajuan_otentik';
    protected $guarded = ['id'];
    public $timestamps = false;
    public function destinasi() { return $this->belongsTo(Destinasi::class, 'destinasi_id'); }
    public function diajukanOleh() { return $this->belongsTo(User::class, 'diajukan_oleh'); }
    public function ditinjauOleh() { return $this->belongsTo(User::class, 'ditinjau_oleh'); }
}
