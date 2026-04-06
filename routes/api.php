<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\IndonesiaDestinationController;
use App\Http\Controllers\Api\InternationalTourController;
use App\Http\Controllers\Api\ManadoTourController;
use App\Http\Controllers\Api\TourController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API
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
