<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ApiPaginationRequest;
use App\Services\ApiPaginationService;
use App\Models\Novel;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class NovelController extends Controller
{
    protected $paginationService;

    public function __construct(ApiPaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ApiPaginationRequest $request) : JsonResponse
    {
        $query = Novel::query();

        $fieldMapping = [
            'title' => 'title',
            'status' => 'status',
            'views' => 'total_views',
        ];

        try {
            $data = $this->paginationService->handle(
                $query, 
                $request, 
                ['genres'], // Relasi Eager Loading
                $fieldMapping
            );

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created novel.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255|unique:novels,title',
            'synopsis'    => 'required|string',
            'status'      => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar
        ]);

        try {
            if ($request->hasFile('cover_image')) {
                // Simpan file ke storage/app/public/covers
                $path = $request->file('cover_image')->store('covers', 'public');
                $validated['cover_image'] = $path;
            }
                
            // Slug dibuat otomatis oleh logic 'booted' di Model Novel.php
            $novel = Novel::create($validated);

            // Jika ada genre yang dikirim (misal: array ID)
            if ($request->has('genre_ids')) {
                $novel->genres()->sync($request->genre_ids);
            }

            // Jika ada tag yang dikirim (misal: array ID)
            if ($request->has('tag_ids')) {
                $novel->tags()->sync($request->tag_ids);
            }

            return response()->json([
                'success' => true,
                'message' => 'Novel berhasil dibuat',
                'data'    => $novel->load('genres')
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified novel by SLUG (untuk halaman detail).
     */
    public function show($slug): JsonResponse
    {
        $novel = Novel::with(['genres', 'tags'])->where('slug', $slug)->first();

        if (!$novel) {
            return response()->json(['success' => false, 'message' => 'Novel tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $novel
        ]);
    }

    /**
     * Update the specified novel.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $novel = Novel::find($id);

        if (!$novel) {
            return response()->json(['success' => false, 'message' => 'Novel tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'title'       => 'sometimes|required|string|max:255|unique:novels,title,' . $id,
            'synopsis'    => 'sometimes|required|string',
            'status'      => 'sometimes|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            if ($request->hasFile('cover_image')) {
                // Hapus foto lama jika ada
                if ($novel->cover_image) {
                    Storage::disk('public')->delete($novel->cover_image);
                }
                
                // Simpan foto baru
                $path = $request->file('cover_image')->store('covers', 'public');
                $validated['cover_image'] = $path;
            }

            if (isset($validated['title'])) {
                $validated['slug'] = Str::slug($validated['title']);
            }

            $novel->update($validated);

            if ($request->has('genre_ids')) {
                $novel->genres()->sync($request->genre_ids);
            }

            return response()->json([
                'success' => true,
                'message' => 'Novel berhasil diperbarui',
                'data'    => $novel->load('genres')
            ]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified novel.
     */
    public function destroy($id): JsonResponse
    {
        $novel = Novel::find($id);

        if (!$novel) {
            return response()->json(['success' => false, 'message' => 'Novel tidak ditemukan'], 404);
        }

        try {
            // Hapus relasi di pivot table (optional, tergantung config database)
            $novel->genres()->detach();
            $novel->delete();

            return response()->json([
                'success' => true,
                'message' => 'Novel berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'title' => 'required|string|max:255',
    //         'synopsis' => 'nullable|string',
    //         'author' => 'required|string|max:255',
    //         'genre_ids' => 'required|array|exists:genres,id',
    //         'genre_ids.*' => 'exists:genres,id',
    //         'tag_ids' => 'nullable|array|exists:tags,id',
    //         'tag_ids.*' => 'exists:tags,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $novel = Novel::create([
    //         'title' => $request->title,
    //         'synopsis' => $request->synopsis
    //     ]);

    //     // Attach genres and tags
    //     $novel->genres()->attach($request->genre_ids);
    //     if ($request->has('tag_ids')) {
    //         $novel->tags()->attach($request->tag_ids);
    //     }

    //     // 

    //     // Load relationships for response
    //     $novel->load('genres', 'tags');

    //     return response()->json($novel, 201);
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(string $id)
    // {
    //     //
    // }
}
