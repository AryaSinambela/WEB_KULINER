<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MenuDestinasi extends Model {
    protected $table = 'menu_destinasi';
    protected $guarded = ['id'];
    public $timestamps = false;
    public function destinasi() { return $this->belongsTo(Destinasi::class, 'destinasi_id'); }
}
