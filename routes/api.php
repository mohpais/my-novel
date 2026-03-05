<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AssetController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GenreController;
use App\Http\Controllers\API\MasterController;
use App\Http\Controllers\API\NovelController;
use App\Http\Controllers\API\UserController;

Route::get('welcome', function (Request $request) {
    return response()->json(['message' => 'Welcome to My Novel Management System API'], 200);
});

Route::get('/debug-headers', function (Request $request) {
    return response()->json([
        'authorization' => $request->header('Authorization'),
        'bearer_token' => $request->bearerToken(),
        'headers' => $request->headers->all(),
    ]);
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware(['jwt.auth'])->group(function () {
        Route::post('change-profile-picture', [AuthController::class, 'updatePicture']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::prefix('master')->group(function () {
    Route::middleware(['jwt.auth', 'role:admin,superadmin'])->group(function () {
        Route::get('roles', [MasterController::class, 'getRoles']);
        Route::get('genres', [MasterController::class, 'getGenres']);
        Route::get('tags', [MasterController::class, 'getTags']);
    });
});

Route::prefix('genre')->group(function () {
    Route::middleware(['jwt.auth', 'role:admin,superadmin'])->group(function () {
        Route::get('list/{page}/{limit}', [GenreController::class, 'index']);
        Route::get('options', [GenreController::class, 'options']);
        Route::get('{slug}', [GenreController::class, 'show']);
        Route::post('create', [GenreController::class, 'store']);
    });
});

Route::middleware(['jwt.auth'])->prefix('novel')->group(function () {
    Route::post('list/{page}/{per_page}', [NovelController::class, 'index']);
    // Route::post('create', [RequestController::class, 'store']);
});

Route::middleware(['jwt.auth', 'role:admin,superadmin'])->prefix('user')->group(function () {
    Route::post('list/{page}/{per_page}', [UserController::class, 'list']);
    Route::post('create', [UserController::class, 'create']);
    Route::delete('{id}', [UserController::class, 'destroy']);
});

Route::any('{path}', function() {
    return response()->json([
        'message' => 'Sorry, the API you were looking for was not found!',
        'success' => false
    ], 404);
})->where('path', '.*');

