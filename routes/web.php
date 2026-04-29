<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WallpaperController;
use App\Http\Controllers\CustomerAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/search-suggestions', [HomeController::class, 'searchSuggestions'])->name('api.search-suggestions');

// Auth routes
Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login'])->name('customer.login');
Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register.form');
Route::post('/register', [CustomerAuthController::class, 'register'])->name('customer.register');
Route::get('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

// Explore / Search
Route::get('/explore', [WallpaperController::class, 'explore'])->name('explore');

// TikTok style feed
Route::get('/feed', [WallpaperController::class, 'feed'])->name('feed');

// User Upload
Route::get('/upload', [WallpaperController::class, 'uploadForm'])->name('upload');
Route::post('/upload', [WallpaperController::class, 'upload'])->name('upload.submit');

// Admin Moderation
Route::get('/admin/moderate', [WallpaperController::class, 'moderationIndex'])->name('admin.moderate');
Route::post('/admin/moderate/{id}/approve', [WallpaperController::class, 'approve'])->name('admin.approve');
Route::post('/admin/moderate/{id}/reject', [WallpaperController::class, 'reject'])->name('admin.reject');

// Wallpaper detail
Route::get('/wallpaper/{id}', [WallpaperController::class, 'show'])->name('wallpaper.show');
Route::post('/wallpaper/{id}/like', [WallpaperController::class, 'like'])->name('wallpaper.like');
Route::post('/wallpaper/{id}/comment', [WallpaperController::class, 'storeComment'])->name('wallpaper.comment');
Route::get('/wallpaper/{id}/download', [WallpaperController::class, 'download'])->name('wallpaper.download');

// Categories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::post('/categories/quick-create', [CategoryController::class, 'quickCreate'])->name('categories.quick-create');

// Albums
Route::get('/album/{id}', [AlbumController::class, 'show'])->name('album.show');

// Artist profile
Route::get('/profile', [ArtistController::class, 'myProfile'])->name('profile');
Route::post('/profile', [ArtistController::class, 'updateProfile'])->name('profile.update');
Route::get('/artist/{id}', [ArtistController::class, 'show'])->name('artist.show');
Route::post('/wallpaper/{id}/toggle-status', [ArtistController::class, 'toggleStatus'])->name('wallpaper.toggle-status');
Route::get('/liked', [ArtistController::class, 'likedPhotosPage'])->name('profile.liked');
Route::get('/uploaded', [ArtistController::class, 'uploadedPhotosPage'])->name('profile.uploaded');
Route::post('/notifications/read-all', [ArtistController::class, 'readAllNotifications'])->name('notifications.read-all');

