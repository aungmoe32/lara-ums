<?php

use App\Models\Tenant;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ModuleController;
use App\Models\User;

Route::get('/', function () {
    return redirect()->route('tenants.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tenant management routes
    Route::resource('tenants', TenantController::class);

    // Module management routes
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::post('/modules/{module}/toggle', [ModuleController::class, 'toggleStatus'])->name('modules.toggle');
});

require __DIR__ . '/auth.php';
