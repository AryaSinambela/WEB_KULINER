<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    const UPDATED_AT = null;
    protected $table = 'ulasan';
    protected $guarded = [];

    public function user() { return $this->belongsTo(User::class); }
    public function destinasi() { return $this->belongsTo(Destinasi::class); }
}