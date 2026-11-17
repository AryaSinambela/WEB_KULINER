<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriMakanan extends Model
{
    protected $table = 'kategori_makanan';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function destinasi()
    {
        return $this->hasMany(Destinasi::class, 'kategori_id');
    }
}