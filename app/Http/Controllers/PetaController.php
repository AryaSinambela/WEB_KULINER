<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Models\KategoriMakanan;
use App\Models\Wilayah;
use App\Models\WisataAlam;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    public function index()
    {
        if (Destinasi::count() < 5) {
            app(\Database\Seeders\DatabaseSeeder::class)->run();
        }

        $kategoris = KategoriMakanan::all();
        $wilayahs = Wilayah::orderBy('kabupaten')->get();

        return view('peta.index', compact('kategoris', 'wilayahs'));
    }

    public function data(Request $request)
    {
        $query = Destinasi::with(['wilayah', 'kategori', 'fotoUtama', 'ulasans', 'pemilik', 'menus'])
            ->where('status_verifikasi', 'disetujui');

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($sub) use ($keyword) {
                $sub->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('alamat', 'like', "%{$keyword}%")
                    ->orWhere('deskripsi', 'like', "%{$keyword}%")
                    ->orWhereHas('menus', function ($m) use ($keyword) {
                        $m->where('nama_menu', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($request->boolean('otentik')) {
            $query->where('is_otentik_batak', true);
        }

        if ($request->filled('kategori')) {
            $kategoriIds = is_array($request->kategori) ? $request->kategori : explode(',', $request->kategori);
            $query->whereIn('kategori_id', array_filter($kategoriIds));
        }

        if ($request->filled('wilayah_id')) {
            $query->where('wilayah_id', $request->wilayah_id);
        }

        if ($request->filled('kabupaten')) {
            $query->whereHas('wilayah', function ($w) use ($request) {
                $w->where('kabupaten', $request->kabupaten);
            });
        }

        if ($request->filled('harga_max')) {
            $query->where(function ($q) use ($request) {
                $q->whereNull('harga_min')
                  ->orWhere('harga_min', '<=', (int) $request->harga_max);
            });
        }

        // Filter Buka Sekarang berdasarkan jam_buka dan jam_tutup
        if ($request->boolean('buka_sekarang')) {
            $now = Carbon::now('Asia/Jakarta')->format('H:i:s');
            $query->whereNotNull('jam_buka')
                  ->whereNotNull('jam_tutup')
                  ->where('jam_buka', '<=', $now)
                  ->where('jam_tutup', '>=', $now);
        }

        $destinasi = $query->get();

        // Filter Radius (Haversine formula jika user mengirimkan lat, lng, dan radius km)
        if ($request->filled(['lat', 'lng', 'radius']) && (float) $request->radius > 0) {
            $lat = (float) $request->lat;
            $lng = (float) $request->lng;
            $radius = (float) $request->radius;

            $destinasi = $destinasi->filter(function ($item) use ($lat, $lng, $radius) {
                $distance = $this->haversine($lat, $lng, (float) $item->latitude, (float) $item->longitude);
                $item->jarak_km = round($distance, 1);
                return $distance <= $radius;
            })->values();
        } elseif ($request->filled(['lat', 'lng'])) {
            $lat = (float) $request->lat;
            $lng = (float) $request->lng;
            $destinasi->each(function ($item) use ($lat, $lng) {
                $item->jarak_km = round($this->haversine($lat, $lng, (float) $item->latitude, (float) $item->longitude), 1);
            });
        }

        $payload = $destinasi->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'pemilik' => $item->pemilik->nama ?? 'Mitra Kuliner Toba',
                'menu_andalan' => $item->menus->pluck('nama_menu')->take(3)->values(),
                'alamat' => $item->alamat,
                'kecamatan' => $item->wilayah->nama_kecamatan ?? '',
                'kabupaten' => $item->wilayah->kabupaten ?? '',
                'kategori' => $item->kategori->nama ?? 'Kuliner',
                'kategori_id' => $item->kategori_id,
                'latitude' => (float) $item->latitude,
                'longitude' => (float) $item->longitude,
                'harga_min' => $item->harga_min,
                'harga_max' => $item->harga_max,
                'jam_buka' => $item->jam_buka ? substr($item->jam_buka, 0, 5) : null,
                'jam_tutup' => $item->jam_tutup ? substr($item->jam_tutup, 0, 5) : null,
                'is_otentik_batak' => (bool) $item->is_otentik_batak,
                'rating_wisatawan' => $item->rating_wisatawan ?: 0,
                'rating_lokal' => $item->rating_lokal ?: 0,
                'foto' => $item->fotoUtama ? asset('storage/' . $item->fotoUtama->path_foto) : null,
                'jarak_km' => $item->jarak_km ?? null,
                'url_detail' => route('destinasi.show', $item->id),
                'url_rute' => route('destinasi.rute', $item->id),
            ];
        });

        // Muat juga titik Wisata Alam Kaldera Toba sebagai layer pelengkap
        $wisataAlamQuery = WisataAlam::with('wilayah');
        if ($request->filled('wilayah_id')) {
            $wisataAlamQuery->where('wilayah_id', $request->wilayah_id);
        }
        $wisataAlam = $wisataAlamQuery->get()->map(function ($wa) {
            return [
                'id' => $wa->id,
                'nama' => $wa->nama,
                'deskripsi' => $wa->deskripsi,
                'kecamatan' => $wa->wilayah->nama_kecamatan ?? '',
                'kabupaten' => $wa->wilayah->kabupaten ?? '',
                'latitude' => (float) $wa->latitude,
                'longitude' => (float) $wa->longitude,
            ];
        });

        return response()->json([
            'total' => $payload->count(),
            'data' => $payload,
            'wisata_alam' => $wisataAlam,
        ]);
    }

    private function haversine(float $lat1, float$lon1, float $lat2, float$lon2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 -$lat1);
        $dLon = deg2rad($lon2 - $lon1);$a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        return $earthRadius * (2 * atan2(sqrt($a), sqrt(1 -$a)));
    }
}