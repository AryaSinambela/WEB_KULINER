<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $query = Destination::query();

        // Filter Wilayah
        if ($request->filled('regency')) {
            $query->where('regency', $request->regency);
        }

        // Filter Label Otentik
        if ($request->filled('authentic') && $request->authentic == '1') {
            $query->where('is_authentic_batak', true);
        }

        $destinations = $query->get();

        return view('destinations.index', compact('destinations'));
    }

    public function show($slug)
    {
        $destination = Destination::where('slug', $slug)
            ->with(['reviews.user'])
            ->firstOrFail();

        return view('destinations.show', compact('destination'));
    }
}