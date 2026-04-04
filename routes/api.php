<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\ManadoTourController;
use App\Http\Controllers\Api\IndonesiaDestinationController;
use App\Http\Controllers\Api\InternationalTourController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API
Route::get('/tours', [TourController::class, 'index']);
Route::get('/tours/{slug}', [TourController::class, 'show']);

// Admin API
Route::apiResource('categories', CategoryController::class);
Route::apiResource('manado-tours', ManadoTourController::class);
Route::apiResource('indonesia-destinations', IndonesiaDestinationController::class);
Route::apiResource('international-tours', InternationalTourController::class);
