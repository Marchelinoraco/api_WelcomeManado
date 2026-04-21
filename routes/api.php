<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\IndonesiaDestinationController;
use App\Http\Controllers\Api\InternationalTourController;
use App\Http\Controllers\Api\ManadoTourController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\GalleryItemController;
use App\Http\Controllers\Api\TransportationBookingController;
use App\Http\Controllers\Api\TransportationController;
use App\Http\Controllers\Api\TravelInfoItemController;
use App\Http\Controllers\Api\HeroImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API
Route::get('/hero-images', [HeroImageController::class, 'publicIndex']);
Route::get('/tours', [TourController::class, 'index']);
Route::get('/tours/{slug}', [TourController::class, 'show']);
Route::get('/wisatalokal/categories', [CategoryController::class, 'byType'])->defaults('type', 'local');
Route::get('/nasional/categories', [CategoryController::class, 'byType'])->defaults('type', 'national');
Route::get('/internasional/regions', [CategoryController::class, 'byType'])->defaults('type', 'international');

Route::get('/wisatalokal/tours', [TourController::class, 'localIndex']);
Route::get('/wisatalokal/tours/{slug}', [TourController::class, 'localShow']);
Route::get('/nasional/tours', [TourController::class, 'nationalIndex']);
Route::get('/nasional/tours/{slug}', [TourController::class, 'nationalShow']);
Route::get('/internasional/tours', [TourController::class, 'internationalIndex']);
Route::get('/internasional/tours/{slug}', [TourController::class, 'internationalShow']);

// Admin API
Route::apiResource('categories', CategoryController::class);
Route::apiResource('manado-tours', ManadoTourController::class);
Route::apiResource('indonesia-destinations', IndonesiaDestinationController::class);
Route::apiResource('international-tours', InternationalTourController::class);
Route::apiResource('hero-images', HeroImageController::class);
Route::apiResource('hotels', HotelController::class);
Route::apiResource('gallery-items', GalleryItemController::class);
Route::apiResource('transportations', TransportationController::class);
Route::apiResource('transportation-bookings', TransportationBookingController::class);
Route::apiResource('travel-info-items', TravelInfoItemController::class);
