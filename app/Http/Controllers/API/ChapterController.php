<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPaginationRequest;

use App\Models\Chapter;
use App\Models\Novel;

use App\Services\ApiPaginationService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    protected $paginationService;

    public function __construct(ApiPaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }

    /**
     * Mengambil daftar chapter berdasarkan novel tertentu.
     * Route: GET /{novel}/chapters
     */
    public function getByNovel($slug, ApiPaginationRequest $request): JsonResponse
    {
        // Cari novel secara manual menggunakan slug dari URL
        $novel = Novel::where('slug', $slug)->first();

        // Cek apakah novel ada
        if (!$novel) {
            return response()->json([
                'success' => false, 
                'message' => 'Novel dengan slug [' . $slug . '] tidak ditemukan di database.'
            ], 404);
        }

        try {
            // 1. Inisialisasi query melalui relasi novel -> chapters
            // Kita menggunakan $novel->chapters() agar query builder tetap bisa dimanipulasi
            $query = $novel->chapters()->getQuery();

            // 2. Mapping field jika parameter dari frontend berbeda dengan kolom DB
            $fieldMapping = [
                'judulChapter' => 'title',
                'nomor'        => 'number',
                'urutan'       => 'order',
                'tanggalRilis' => 'released_date'
            ];

            // 3. Eksekusi pagination menggunakan service
            // Kita menyertakan relasi 'novel' jika diperlukan di sisi frontend
            $result = $this->paginationService->handle(
                $query, 
                $request, 
                ['novel'], 
                $fieldMapping
            );

            // Tambahkan informasi tambahan tentang novelnya (opsional)
            $result['novel_details'] = [
                'title' => $novel->title,
                'slug'  => $novel->slug,
                'status' => $novel->status
            ];

            return response()->json($result);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat daftar chapter: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil satu data chapter berdasarkan slug untuk halaman baca.
     */
    public function showBySlug($novelSlug, $chapterSlug): JsonResponse
    {
        try {
            // 1. Cari Novel berdasarkan slug
            $novel = \App\Models\Novel::where('slug', $novelSlug)->first();

            if (!$novel) {
                return response()->json(['success' => false, 'message' => 'Novel tidak ditemukan'], 404);
            }

            // 2. Cari Chapter milik novel tersebut berdasarkan slug
            $chapter = $novel->chapters()->where('slug', $chapterSlug)->first();

            if (!$chapter) {
                return response()->json(['success' => false, 'message' => 'Chapter tidak ditemukan'], 404);
            }

            // 3. Ambil Navigasi (Previous & Next) berdasarkan urutan (number/order)
            $prevChapter = $novel->chapters()
                ->where('number', '<', $chapter->number)
                ->orderBy('number', 'desc')
                ->first(['slug', 'title', 'number']);

            $nextChapter = $novel->chapters()
                ->where('number', '>', $chapter->number)
                ->orderBy('number', 'asc')
                ->first(['slug', 'title', 'number']);

            return response()->json([
                'success' => true,
                'data' => [
                    'novel_title' => $novel->title,
                    'chapter'     => $chapter,
                    'navigation'  => [
                        'previous' => $prevChapter,
                        'next'     => $nextChapter,
                    ]
                ]
            ]);

        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Simpan chapter baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'novel_id' => 'required|exists:novels,id',
            'title'    => 'required|string|max:255',
            'number'   => 'required|integer',
            'content'  => 'required|string'
        ]);

        try {
            // 1. Ambil order terakhir dari chapter yang memiliki novel_id yang sama
            $lastOrder = Chapter::where('novel_id', $validated['novel_id'])
                ->max('order');
                
            // 2. Set order baru: jika tidak ada data (null), mulai dari 1. Jika ada, tambah 1.
            $validated['order'] = $lastOrder ? $lastOrder + 1 : 1;

            // 3. Simpan data chapter
            // Slug otomatis dibuat di Model (booted method)
            $chapter = Chapter::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Chapter berhasil ditambahkan',
                'data'    => $chapter
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Batas 2MB
        ]);

        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                
                // Simpan ke folder storage/app/public/chapter-images
                // Nama file dibuat unik secara otomatis
                $path = $file->store('chapter-images', 'public');

                // Generate URL publik
                $url = asset('storage/' . $path);

                return response()->json([
                    'success' => true,
                    'url' => $url
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Update data chapter.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $chapter = Chapter::find($id);

        if (!$chapter) {
            return response()->json(['success' => false, 'message' => 'Chapter tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'title'   => 'sometimes|required|string|max:255',
            'number'  => 'sometimes|required|integer',
            'content' => 'sometimes|required|string',
            'order'   => 'nullable|integer',
        ]);

        try {
            // Jika title berubah, slug akan diupdate otomatis jika Anda menambahkannya di model
            if (isset($validated['title'])) {
                $validated['slug'] = Str::slug($validated['title']);
            }

            $chapter->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Chapter berhasil diperbarui',
                'data'    => $chapter
            ]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Endpoint untuk menyusun ulang urutan bab secara manual (misal Drag & Drop).
     * Request body: { "chapters": [{ "id": 1, "order": 1 }, { "id": 2, "order": 2 }] }
     */
    public function manualReorder(Request $request): JsonResponse
    {
        $request->validate([
            'chapters' => 'required|array',
            'chapters.*.id' => 'required|exists:chapters,id',
            'chapters.*.order' => 'required|integer',
        ]);

        try {
            foreach ($request->chapters as $item) {
                Chapter::where('id', $item['id'])->update(['order' => $item['order']]);
            }

            return response()->json(['success' => true, 'message' => 'Urutan bab berhasil diperbarui.']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Hapus chapterdan rapikan ulang urutan.
     */
    public function destroy($id): JsonResponse
    {
        $chapter = Chapter::find($id);

        if (!$chapter) {
            return response()->json(['success' => false, 'message' => 'Chapter tidak ditemukan'], 404);
        }

        try {
            $novelId = $chapter->novel_id; // Simpan ID novel sebelum dihapus

            $chapter->delete();
            
            // Rapikan ulang urutan bab untuk novel ini
            $this->reorderChapters($novelId);
            
            return response()->json([
                'success' => true,
                'message' => 'Chapter berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Logika internal untuk merapikan ulang nomor urutan (order) bab.
     * Memastikan tidak ada nomor yang melompat setelah penghapusan.
     */
    private function reorderChapters($novelId): void
    {
        // Ambil semua chapter berdasarkan urutan saat ini
        $chapters = Chapter::where('novel_id', $novelId)
            ->orderBy('order', 'asc')
            ->get();

        // Update urutan satu per satu secara berurutan
        foreach ($chapters as $index => $chapter) {
            $newOrder = $index + 1;
            
            // Hanya update jika order-nya berubah untuk efisiensi database
            if ($chapter->order !== $newOrder) {
                $chapter->update(['order' => $newOrder]);
            }
        }
    }
}