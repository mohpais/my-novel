<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AiAssistantController;
use App\Http\Controllers\API\ChapterController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GenreController;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\MasterController;
use App\Http\Controllers\API\NovelController;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| Utility & Debug Routes
|--------------------------------------------------------------------------
*/
Route::get('welcome', function () {
    return response()->json(['message' => 'Welcome to My Novel Management System API'], 200);
});

Route::get('/debug-headers', function (Request $request) {
    return response()->json([
        'authorization' => $request->header('Authorization'),
        'bearer_token' => $request->bearerToken(),
        'headers' => $request->headers->all(),
    ]);
});

Route::get('/test-queue', function() {
    \App\Jobs\ProcessAiEmbedding::dispatch(1, 'test', 1, 'Cek koneksi queue');
    return "Job berhasil dikirim!";
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware(['jwt.auth'])->group(function () {
        Route::post('change-profile-picture', [AuthController::class, 'updatePicture']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Bisa diakses tanpa Login)
|--------------------------------------------------------------------------
| Rute untuk pembaca di frontend.
*/
Route::prefix('novel')->group(function () {
    // List novel (dengan filter/pagination)
    Route::post('list', [NovelController::class, 'index']);
    
    // Detail novel berdasarkan slug
    Route::get('show/{slug}', [NovelController::class, 'show']);
    
    // List chapter dalam sebuah novel (menggunakan slug novel)
    Route::post('{slug}/chapters', [ChapterController::class, 'getByNovel']);
    
    // Baca isi chapter spesifik
    Route::get('{novelSlug}/chapter/{chapterSlug}', [ChapterController::class, 'showBySlug']);
});

/*
|--------------------------------------------------------------------------
| PRIVATE ROUTES (Wajib Login jwt.auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['jwt.auth'])->group(function () {

    // --- Management Novel (Admin/Author) ---
    Route::prefix('novel')->group(function () {
        Route::post('store', [NovelController::class, 'store']);
        // Gunakan POST + _method=PUT di Form Data jika upload cover_image
        Route::post('update/{id}', [NovelController::class, 'update']); 
        Route::delete('delete/{id}', [NovelController::class, 'destroy']);
    });

    // --- Management Chapter (Admin/Author) ---
    Route::prefix('chapter')->group(function () {
        Route::post('store', [ChapterController::class, 'store']);
        Route::post('update/{id}', [ChapterController::class, 'update']);
        Route::delete('delete/{id}', [ChapterController::class, 'destroy']);
    });

    // --- Admin & Superadmin Only ---
    Route::middleware(['role:admin,superadmin'])->group(function () {
        
        // User Management
        Route::prefix('user')->group(function () {
            Route::post('list/{page}/{per_page}', [UserController::class, 'list']);
            Route::post('create', [UserController::class, 'create']);
            Route::delete('{id}', [UserController::class, 'destroy']);
        });

        // Master Data
        Route::prefix('master')->group(function () {
            Route::get('roles', [MasterController::class, 'getRoles']);
            Route::get('genres', [MasterController::class, 'getGenres']);
            Route::get('tags', [MasterController::class, 'getTags']);
        });

        // Genre Management
        Route::prefix('genre')->group(function () {
            Route::get('list/{page}/{limit}', [GenreController::class, 'index']);
            Route::get('options', [GenreController::class, 'options']);
            Route::get('{slug}', [GenreController::class, 'show']);
            Route::post('create', [GenreController::class, 'store']);
        });

        // Tag Management
        Route::prefix('tag')->group(function () {
            Route::get('list/{page}/{limit}', [TagController::class, 'index']);
            Route::get('options', [TagController::class, 'options']);
            Route::get('{slug}', [TagController::class, 'show']);
            Route::post('create', [TagController::class, 'store']);
        });

        // AI Assistant
        Route::prefix('ai')->group(function () {
            Route::post('ask', [AiAssistantController::class, 'ask']);
        });
    });
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/
Route::any('{path}', function() {
    return response()->json([
        'success' => false,
        'message' => 'Sorry, the API you were looking for was not found!'
    ], 404);
})->where('path', '.*');