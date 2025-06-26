<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Api\GeonamesController;
use App\Http\Controllers\Api\QuoteRequestController;

// SPA entrypoint: Home via Inertia + React
Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

// Protected dashboard (auth + verified)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

// GeoNames API for PLZ-city autofill
Route::get('/api/locations/cities', [GeonamesController::class, 'cities'])
     ->name('api.locations.cities');

// Quote Request API (submitted from React)
Route::post('/api/quote-requests', [QuoteRequestController::class, 'store'])
     ->name('api.quote.requests.store');

// Authentication routes provided by Laravel Breeze or similar
require __DIR__ . '/auth.php';

// Additional settings routes
require __DIR__ . '/settings.php';