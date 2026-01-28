<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EducationFacilityController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Map\PetaController;
use Illuminate\Support\Facades\Route;

// route map (peta sebaran)
Route::get('/', [PetaController::class, 'index'])->name('map.index');

// In routes/web.php or routes/admin.php
Route::prefix('admin')->group(function () {

    // --- Route Dashboard  --
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // --- Route Data fasilitas --
    Route::get('/education-facility', [EducationFacilityController::class, 'index'])->name('admin.education-facility');
    Route::get('/education-facility/create', [EducationFacilityController::class, 'create'])->name('admin.education-facility.create');
    Route::get('/education-facility/store', [EducationFacilityController::class, 'store'])->name('admin.education-facility.store');

    // --- Route Profile controller --
    Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile');
});
