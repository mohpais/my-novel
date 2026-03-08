<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoreEntry;
use App\Models\Location;
use Illuminate\Http\Request;

class WorldBuildingController extends Controller
{
    /**
     * Menyimpan Lore Baru (Budaya, Sejarah, Agama, dll)
     */
    public function storeLore(Request $request)
    {
        $validated = $request->validate([
            'novel_id' => 'required|exists:novels,id',
            'category' => 'required|string', // e.g., 'Religion', 'History'
            'title'    => 'required|string|max:255',
            'content'  => 'required|string'
        ]);

        $lore = LoreEntry::create($validated);

        return response()->json([
            'message' => 'Lore berhasil disimpan dan sedang dipelajari AI.',
            'data'    => $lore
        ], 201);
    }

    /**
     * Menyimpan Lokasi Baru (Geografi)
     */
    public function storeLocation(Request $request)
    {
        $validated = $request->validate([
            'world_id'           => 'required|exists:worlds,id',
            'parent_location_id' => 'nullable|exists:locations,id',
            'name'               => 'required|string',
            'type'               => 'required|string', // e.g., 'Kingdom', 'City'
            'description'        => 'nullable|string',
            'climate'            => 'nullable|string'
        ]);

        $location = Location::create($validated);

        return response()->json([
            'message' => 'Lokasi geografi berhasil dicatat.',
            'data'    => $location
        ], 201);
    }

    /**
     * Mendapatkan semua struktur dunia berdasarkan Novel
     */
    public function index($novelId)
    {
        $lore = LoreEntry::where('novel_id', $novelId)->get();
        $locations = Location::whereHas('world', function($q) use ($novelId) {
            $q->where('novel_id', $novelId);
        })->with('children')->whereNull('parent_location_id')->get();

        return response()->json([
            'world_map' => $locations,
            'encyclopedia' => $lore
        ]);
    }
}