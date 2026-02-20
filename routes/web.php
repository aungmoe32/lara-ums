<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('tenants.index');
})->name('central.home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tenant management routes
    Route::resource('tenants', TenantController::class);

    // Module management routes
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/create', [ModuleController::class, 'create'])->name('modules.create');
    Route::post('/modules', [ModuleController::class, 'store'])->name('modules.store');
    Route::post('/modules/{module}/toggle', [ModuleController::class, 'toggleStatus'])->name('modules.toggle');

    // Central Module Requests
    Route::get('/module-requests', [App\Http\Controllers\ModuleRequestController::class, 'index'])->name('module-requests.index');
    Route::post('/module-requests/{moduleRequest}/approve', [App\Http\Controllers\ModuleRequestController::class, 'approve'])->name('module-requests.approve');
    Route::post('/module-requests/{moduleRequest}/reject', [App\Http\Controllers\ModuleRequestController::class, 'reject'])->name('module-requests.reject');
});

require __DIR__ . '/auth.php';
