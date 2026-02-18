<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Stancl\Tenancy\Database\Models\Domain;

/*
|--------------------------------------------------------------------------
| API Routes - Caddy Domain Verification
|--------------------------------------------------------------------------
|
| This endpoint is called by Caddy server to verify if a domain is
| authorized for automatic SSL certificate issuance.
|
*/

Route::get('/caddy-check', function (Request $request) {
    $domainName = $request->query('domain');

    if (!$domainName) {
        return response('Domain parameter required', 400);
    }

    // Check if domain exists in DB and is VERIFIED
    $exists = Domain::where('domain', $domainName)
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
