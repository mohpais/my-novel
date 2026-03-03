<?php

use Illuminate\Support\Facades\Route;

// Pengecualian untuk gambar default
Route::get('images/{filename}', function ($filename) {
    $path = public_path('images/' . $filename);

    if (file_exists($path)) {
        return Response::file($path);
    }

    // Jika file tidak ditemukan, Anda dapat mengembalikan 404
    abort(404); 
});

// Pengecualian untuk gambar yang di-upload melalui symlink storage
Route::get('storage/{path}', function ($path) {
    $path = public_path('storage/' . $path);

    if (file_exists($path)) {
        return Response::file($path);
    }
    abort(404);
})->where('path', '.*');

Route::view('/{any?}', 'main-view')
    ->name('main-')
    ->where('any', '.*');
