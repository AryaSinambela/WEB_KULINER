<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Models\Itinerary;
use App\Models\ItineraryItem;
use App\Models\WisataAlam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItineraryController extends Controller
{
    public function index()
    {
        $itineraries = Itinerary::with(['items.destinasi.wilayah', 'items.wisataAlam.wilayah'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('itinerary.index', compact('itineraries'));
    }

    public function create()
    {
        $destinasiList = Destinasi::with('wilayah')->where('status_verifikasi', 'disetujui')->get();
        $wisataAlamList = WisataAlam::with('wilayah')->get();

        return view('itinerary.create', compact('destinasiList', 'wisataAlamList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'             => 'required|string|max:255',
            'tanggal_kunjungan' => 'nullable|date',
            'moda'              => 'nullable|string|max:30',
            'destinasi_id'      => 'nullable|exists:destinasi,id',
            'hari'              => 'nullable|integer|min:1',
            'waktu_tiba'        => 'nullable',
            'catatan'           => 'nullable|string',
        ]);

        $itinerary = Itinerary::create([
            'user_id'           => Auth::id(),
            'judul'             => $validated['judul'],
            'tanggal_kunjungan' => $validated['tanggal_kunjungan'] ?? null,
            'moda'              => $validated['moda'] ?? 'Mobil',
            'status'            => 'aktif',
        ]);

        // Jika dibuat langsung dari Modal Simpan Rencana di halaman detail destinasi (U10)
        if (!empty($validated['destinasi_id'])) {
            ItineraryItem::create([
                'itinerary_id' => $itinerary->id,
                'destinasi_id' => $validated['destinasi_id'],
                'urutan'       => 1,
                'hari'         => $validated['hari'] ?? 1,
                'waktu_tiba'   => $validated['waktu_tiba'] ?? null,
                'catatan'      => $validated['catatan'] ?? null,
            ]);
        }

        return redirect()->route('itinerary.show', $itinerary)
            ->with('success', 'Rencana kunjungan baru berhasil dibuat!');
    }

    public function show(Itinerary $itinerary)
    {
        abort_if($itinerary->user_id !== Auth::id(), 403);

        $itinerary->load(['items' => function ($q) {
            $q->orderBy('hari')->orderBy('urutan');
        }, 'items.destinasi.wilayah', 'items.wisataAlam.wilayah']);

        $destinasiList = Destinasi::with('wilayah')->where('status_verifikasi', 'disetujui')->get();
        $wisataAlamList = WisataAlam::with('wilayah')->get();

        return view('itinerary.show', compact('itinerary', 'destinasiList', 'wisataAlamList'));
    }

    public function update(Request $request, Itinerary $itinerary)
    {
        abort_if($itinerary->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'judul'             => 'required|string|max:255',
            'tanggal_kunjungan' => 'nullable|date',
            'moda'              => 'nullable|string|max:30',
            'status'            => 'nullable|in:draf,aktif,selesai',
        ]);

        $itinerary->update($validated);
        return back()->with('success', 'Rencana kunjungan diperbarui.');
    }

    public function tambahStop(Request $request, Itinerary $itinerary)
    {
        abort_if($itinerary->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'destinasi_id'   => 'nullable|exists:destinasi,id',
            'wisata_alam_id' => 'nullable|exists:wisata_alam,id',
            'hari'           => 'nullable|integer|min:1',
            'waktu_tiba'     => 'nullable',
            'catatan'        => 'nullable|string',
        ]);

        if (empty($validated['destinasi_id']) && empty($validated['wisata_alam_id'])) {
            return back()->with('error', 'Pilih minimal satu destinasi kuliner atau wisata alam.');
        }

        $nextUrutan = ($itinerary->items()->max('urutan') ?? 0) + 1;

        ItineraryItem::create([
            'itinerary_id'   => $itinerary->id,
            'destinasi_id'   => $validated['destinasi_id'] ?? null,
            'wisata_alam_id' => $validated['wisata_alam_id'] ?? null,
            'urutan'         => $nextUrutan,
            'hari'           => $validated['hari'] ?? 1,
            'waktu_tiba'     => $validated['waktu_tiba'] ?? null,
            'catatan'        => $validated['catatan'] ?? null,
        ]);

        return back()->with('success', 'Persinggahan berhasil ditambahkan ke rencana kunjungan.');
    }

    public function hapusStop(ItineraryItem $item)
    {
        abort_if($item->itinerary->user_id !== Auth::id(), 403);
        $item->delete();
        return back()->with('success', 'Persinggahan dihapus dari rencana.');
    }

    public function destroy(Itinerary $itinerary)
    {
        abort_if($itinerary->user_id !== Auth::id(), 403);
        $itinerary->delete();
        return redirect()->route('itinerary.index')->with('success', 'Rencana kunjungan berhasil dihapus.');
    }
}