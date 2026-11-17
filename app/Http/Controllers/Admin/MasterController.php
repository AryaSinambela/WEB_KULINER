<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriMakanan;
use App\Models\Wilayah;

class MasterController extends Controller
{
    public function index()
    {
        $kategoris = KategoriMakanan::withCount('destinasi')->get();
        $wilayahs = Wilayah::withCount('destinasi')->orderBy('kabupaten')->get();
        return view('admin.master', compact('kategoris', 'wilayahs'));
    }
}