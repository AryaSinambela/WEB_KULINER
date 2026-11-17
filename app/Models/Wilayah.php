<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function destinasi()
    {
        return $this->hasMany(Destinasi::class, 'wilayah_id');
    }

    public function wisataAlam()
    {
        return $this->hasMany(WisataAlam::class, 'wilayah_id');
    }
}