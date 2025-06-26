<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuoteRequestController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\GeonamesController;

// Public route for guest quote requests
Route::post('quote-requests', [QuoteRequestController::class, 'store']);

// Geonames API for countries and cities
Route::get('api/locations/cities', [GeonamesController::class, 'cities']);

// Protected routes for authenticated users
Route::middleware('auth:sanctum')->group(function () {
    // Quote Requests (view, update, delete)
    Route::apiResource('quote-requests', QuoteRequestController::class)
         ->except(['store']);

    // Addresses
    Route::apiResource('addresses', AddressController::class);

    // Orders
    Route::apiResource('orders', OrderController::class);

});
