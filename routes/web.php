<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EducationFacilityController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Map\PetaController;
use Illuminate\Support\Facades\Route;

// route map (peta sebaran)
Route::get('/', [PetaController::class, 'index'])->name('map.index');

// Map API Routes (public)
Route::prefix('api/map')->group(function () {
    Route::get('/facilities', [PetaController::class, 'getFacilities'])->name('api.map.facilities');
    Route::get('/jenjang/{jenjang}', [PetaController::class, 'getFacilitiesByJenjang'])->name('api.map.jenjang');
    Route::get('/search', [PetaController::class, 'search'])->name('api.map.search');
    Route::get('/detail/{id}', [PetaController::class, 'getDetail'])->name('api.map.detail');
});

// Protected Admin Routes
Route::middleware('auth')->prefix('admin')->group(function () {

    // --- Route Dashboard  --
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // --- Route Data fasilitas --
    Route::get('/education-facility', [EducationFacilityController::class, 'index'])->name('admin.education-facility');
    Route::get('/education-facility/create', [EducationFacilityController::class, 'create'])->name('admin.education-facility.create');
    Route::post('/education-facility', [EducationFacilityController::class, 'store'])->name('admin.education-facility.store');
    Route::get('/education-facility/{educationFacility}/edit', [EducationFacilityController::class, 'edit'])->name('admin.education-facility.edit');
    Route::put('/education-facility/{educationFacility}', [EducationFacilityController::class, 'update'])->name('admin.education-facility.update');
    Route::delete('/education-facility/{educationFacility}', [EducationFacilityController::class, 'destroy'])->name('admin.education-facility.destroy');

    // --- Route Profile admin ---
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('admin.profile');
    Route::patch('/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('admin.profile.destroy');

    // --- Route Users (super-admin only) ---
    Route::middleware('can:super-admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });
});

require __DIR__.'/auth.php';

