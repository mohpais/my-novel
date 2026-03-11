<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    /**
     * Menangani upload gambar dari berbagai modul.
     */
    public function uploadImage(Request $request): JsonResponse
    {
        // 1. Validasi input
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Batas 2MB
            'folder' => 'nullable|string|alpha_dash' // Nama folder tujuan (misal: chapters, characters)
        ]);

        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                
                // 2. Tentukan folder tujuan (default ke 'general' jika tidak diisi)
                $folder = $request->input('folder', 'general');
                $targetPath = "uploads/" . $folder;

                // 3. Simpan file dengan nama unik
                // File akan disimpan di storage/app/public/uploads/{folder}
                $path = $file->store($targetPath, 'public');

                // 4. Kembalikan URL publik
                $url = asset('storage/' . $path);

                return response()->json([
                    'success' => true,
                    'url' => $url,
                    'path' => $path // Disimpan untuk referensi database jika perlu
                ]);
            }

            return response()->json(['success' => false, 'message' => 'File tidak ditemukan'], 400);

        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function deleteImage(Request $request)
    {
        foreach ($request->images as $url) {

            $path = str_replace(url('/storage'), 'public', $url);

            if(Storage::exists($path)){
                Storage::delete($path);
            }

        }

        return response()->json(['success'=>true]);
    }
}
