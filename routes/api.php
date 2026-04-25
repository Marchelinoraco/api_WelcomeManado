<?php

use App\Http\Controllers\Api\AdminActivityLogController;
use App\Http\Controllers\Api\AboutStorySectionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogCategoryController;
use App\Http\Controllers\Api\BlogHeroImageController;
use App\Http\Controllers\Api\BlogPostController;
use App\Http\Controllers\Api\UploadController;
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
use App\Http\Controllers\Api\NasionalHeroImageController;
use App\Http\Controllers\Api\TeamMemberController;
use App\Http\Controllers\Api\HeroImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ─── Auth (Public) ───────────────────────────────────────────
Route::post('/admin/login', [AuthController::class, 'login']);

// ─── Public API ──────────────────────────────────────────────
Route::get('/hero-images', [HeroImageController::class, 'publicIndex']);
Route::get('/nasional-hero-images', [NasionalHeroImageController::class, 'publicIndex']);
Route::get('/team-members', [TeamMemberController::class, 'index']);
Route::get('/about-story-section', [AboutStorySectionController::class, 'show']);
Route::get('/gallery-items', [GalleryItemController::class, 'index']);
Route::get('/gallery-items/{gallery_item}', [GalleryItemController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/hotels', [HotelController::class, 'index']);
Route::get('/hotels/{hotel}', [HotelController::class, 'show']);
Route::get('/travel-info-items', [TravelInfoItemController::class, 'index']);
Route::get('/travel-info-items/{travel_info_item}', [TravelInfoItemController::class, 'show']);
Route::get('/transportations', [TransportationController::class, 'index']);
Route::get('/transportations/{transportation}', [TransportationController::class, 'show']);
Route::post('/transportation-bookings', [TransportationBookingController::class, 'store']);
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

// Blog
Route::get('/blog/categories', [BlogCategoryController::class, 'index']);
Route::get('/blog/posts', [BlogPostController::class, 'index']);
Route::get('/blog/posts/{slug}', [BlogPostController::class, 'show']);
Route::get('/blog/hero-image', [BlogHeroImageController::class, 'show']);

// ─── Admin API (Protected) ──────────────────────────────────
Route::middleware(['auth:sanctum', 'log.admin'])->group(function () {
    // Auth
    Route::post('/admin/logout', [AuthController::class, 'logout']);
    Route::get('/admin/me', [AuthController::class, 'me']);

    // Activity Logs
    Route::get('/admin/activity-logs', [AdminActivityLogController::class, 'index']);

    // About Story
    Route::get('/admin/about-story-section', [AboutStorySectionController::class, 'adminShow']);
    Route::put('/admin/about-story-section', [AboutStorySectionController::class, 'upsert']);
    Route::post('/admin/about-story-section', [AboutStorySectionController::class, 'upsert']);

    // Resources
    // Entities that are publicly readable (except index/show):
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    Route::apiResource('hero-images', HeroImageController::class)->except(['index', 'show']);
    Route::get('/admin/nasional-hero-images', [NasionalHeroImageController::class, 'index']);
    Route::apiResource('nasional-hero-images', NasionalHeroImageController::class)->except(['index', 'show']);
    Route::get('/admin/team-members', [TeamMemberController::class, 'adminIndex']);
    Route::apiResource('team-members', TeamMemberController::class)->except(['index', 'show']);
    Route::apiResource('hotels', HotelController::class)->except(['index', 'show']);
    Route::apiResource('gallery-items', GalleryItemController::class)->except(['index', 'show']);
    Route::apiResource('transportations', TransportationController::class)->except(['index', 'show']);
    Route::apiResource('travel-info-items', TravelInfoItemController::class)->except(['index', 'show']);

    // Admin-only entirely (Tours manage themselves via TourController for public):
    Route::apiResource('manado-tours', ManadoTourController::class);
    Route::post('manado-tours/{id}/toggle-featured', [ManadoTourController::class, 'toggleFeatured']);
    Route::apiResource('indonesia-destinations', IndonesiaDestinationController::class);
    Route::post('indonesia-destinations/{id}/toggle-featured', [IndonesiaDestinationController::class, 'toggleFeatured']);
    Route::apiResource('international-tours', InternationalTourController::class);
    Route::post('international-tours/{id}/toggle-featured', [InternationalTourController::class, 'toggleFeatured']);

    // Bookings are publicly creatable, but admin-only readable/updatable/deletable:
    Route::apiResource('transportation-bookings', TransportationBookingController::class)->except(['store']);

    // Blog Management
    Route::apiResource('blog-categories', BlogCategoryController::class)->except(['index']);
    Route::apiResource('blog-posts', BlogPostController::class)->except(['index', 'show']);

    // Image Upload (for CKEditor)
    Route::post('/upload/image', [UploadController::class, 'image']);

    // Blog Hero Image
    Route::get('/admin/blog-hero-image', [BlogHeroImageController::class, 'adminShow']);
    Route::post('/blog-hero-image', [BlogHeroImageController::class, 'upsert']);
    Route::delete('/blog-hero-image', [BlogHeroImageController::class, 'destroy']);
});
