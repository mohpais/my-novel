<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AssetController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BudgetController;
use App\Http\Controllers\API\FinanceController;
use App\Http\Controllers\API\MasterController;
use App\Http\Controllers\API\RequestController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\QuestionController;

Route::get('welcome', function (Request $request) {
    return response()->json(['message' => 'Welcome to Throne of Fractured Fates API'], 200);
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

Route::middleware(['jwt.auth'])->prefix('asset')->group(function () {
    Route::post('create', [AssetController::class, 'store']);
    Route::get('show', [AssetController::class, 'show']);
});

Route::middleware(['jwt.auth'])->prefix('budget')->group(function () {
    Route::get('list', [BudgetController::class, 'list']);
    Route::get('options', [BudgetController::class, 'options']);
});

Route::middleware(['jwt.auth'])->prefix('finance')->group(function () {
    Route::post('list/{type}/{page}/{per_page}', [FinanceController::class, 'list']);
    Route::middleware(['role:admin,superadmin'])->group(function () {
        Route::get('count', [FinanceController::class, 'count']);
    });
});

Route::prefix('master')->group(function () {
    Route::middleware(['jwt.auth', 'role:admin,superadmin'])->group(function () {
        Route::get('roles', [MasterController::class, 'getRoles']);
    });
});

Route::middleware(['jwt.auth'])->prefix('request')->group(function () {
    Route::get('draft', [RequestController::class, 'draft']);
    Route::get('{request_code}', [RequestController::class, 'get']);
    Route::post('list/{page}/{per_page}', [RequestController::class, 'list']);
    Route::post('pending/{page}/{per_page}', [RequestController::class, 'pending']);
    Route::post('history/{page}/{per_page}', [RequestController::class, 'history']);
    Route::post('create', [RequestController::class, 'store']);
    Route::post('approve', [RequestController::class, 'approve']);
});

Route::middleware(['jwt.auth', 'role:admin,superadmin'])->prefix('user')->group(function () {
    Route::post('list/{page}/{per_page}', [UserController::class, 'list']);
    Route::post('create', [UserController::class, 'create']);
    Route::delete('{id}', [UserController::class, 'destroy']);
});

Route::middleware(['jwt.auth'])->prefix('question')->group(function () {
    Route::get('list', [QuestionController::class, 'list']);
});

Route::any('{path}', function() {
    return response()->json([
        'message' => 'Sorry, the API you were looking for was not found!',
        'success' => false
    ], 404);
})->where('path', '.*');

