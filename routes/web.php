<?php

use App\Models\Tenant;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ModuleController;
use App\Models\User;

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

/*
|--------------------------------------------------------------------------
| Caddy Domain Verification Endpoint
|--------------------------------------------------------------------------
| This endpoint is called by Caddy server to verify if a domain is
| authorized for automatic SSL certificate issuance.
*/
Route::get('/api/caddy-check', function (Illuminate\Http\Request $request) {
    $domainName = $request->query('domain');

    if (!$domainName) {
        return response('Domain parameter required', 400);
    }

    // Check if domain exists in DB and is VERIFIED
    $exists = Stancl\Tenancy\Database\Models\Domain::where('domain', $domainName)
        ->whereNotNull('verified_at')
        ->exists();

    if ($exists) {
        return response('OK', 200);
    }

    // Also allow central domains (critical!)
    $centralDomains = config('tenancy.central_domains', []);
    foreach ($centralDomains as $centralDomain) {
        if ($domainName === $centralDomain || str_ends_with($domainName, '.' . $centralDomain)) {
            return response('OK', 200);
        }
    }

    return response('Unauthorized', 404);
});

require __DIR__ . '/auth.php';
