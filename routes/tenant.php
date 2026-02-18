<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use Modules\Product\Models\Product;
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
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

Route::group([
    'prefix' => '/{tenant}',
    'middleware' => [InitializeTenancyByPath::class],
], function () {
    Route::get('/foo', function () {
        dd(Product::all());
        return 'foo';
    })->name('foo');
});

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });


    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('tenant.profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('tenant.profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('tenant.profile.destroy');

        Route::resource('roles', App\Http\Controllers\RoleController::class);
        Route::resource('permissions', App\Http\Controllers\PermissionController::class);
        Route::resource('features', App\Http\Controllers\FeatureController::class);
        Route::resource('users', App\Http\Controllers\UserController::class);

        // Custom Domain Management
        Route::resource('domains', App\Http\Controllers\Tenant\DomainController::class)->except(['edit', 'update']);
        Route::post('/domains/{domain}/verify', [App\Http\Controllers\Tenant\DomainController::class, 'verify'])->name('domains.verify');

        // Tenant Module Requests
        Route::get('/modules', [App\Http\Controllers\Tenant\ModuleRequestController::class, 'index'])->name('tenant.modules.index');
        Route::post('/modules/request', [App\Http\Controllers\Tenant\ModuleRequestController::class, 'store'])->name('tenant.modules.request');
        Route::post('/modules/install', [App\Http\Controllers\Tenant\ModuleRequestController::class, 'install'])->name('tenant.modules.install');
        Route::post('/modules/uninstall', [App\Http\Controllers\Tenant\ModuleRequestController::class, 'uninstall'])->name('tenant.modules.uninstall');
    });

    require __DIR__ . '/auth.php';
});
