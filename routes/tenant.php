<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::resource('roles', App\Http\Controllers\RoleController::class);
        Route::resource('permissions', App\Http\Controllers\PermissionController::class);
        Route::resource('features', App\Http\Controllers\FeatureController::class);
        Route::resource('users', App\Http\Controllers\UserController::class);

        // Tenant Module Requests
        Route::get('/modules', [App\Http\Controllers\Tenant\ModuleRequestController::class, 'index'])->name('tenant.modules.index');
        Route::post('/modules/request', [App\Http\Controllers\Tenant\ModuleRequestController::class, 'store'])->name('tenant.modules.request');
        Route::post('/modules/install', [App\Http\Controllers\Tenant\ModuleRequestController::class, 'install'])->name('tenant.modules.install');
        Route::post('/modules/uninstall', [App\Http\Controllers\Tenant\ModuleRequestController::class, 'uninstall'])->name('tenant.modules.uninstall');
    });

    require __DIR__ . '/auth.php';
});
