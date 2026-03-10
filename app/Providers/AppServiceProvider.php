<?php

namespace App\Providers;


use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\EnsureProviderVerified;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Route::aliasMiddleware('jwt.auth', AuthMiddleware::class);
        Route::aliasMiddleware('role', RoleMiddleware::class);
        Route::aliasMiddleware('provider.verified', EnsureProviderVerified::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\Character::observe(\App\Observers\CharacterObserver::class);
        \App\Models\Location::observe(\App\Observers\LocationObserver::class);
        \App\Models\LoreEntry::observe(\App\Observers\LoreEntryObserver::class);
        \App\Models\CharacterPower::observe(\App\Observers\CharacterPowerObserver::class);
        \App\Models\CharacterPower::observe(\App\Observers\ChapterObserver::class);
    }
}
