<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EducationFacilityController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Map\PetaController;
use Illuminate\Support\Facades\Route;

// route map (peta sebaran)
Route::get('/', [PetaController::class, 'index'])->name('map.index');

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
});

require __DIR__.'/auth.php';
