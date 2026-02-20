<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__ . '/../routes/web.php',
        // api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        using: function () {
            $centralDomains = config('tenancy.central_domains');

            foreach ($centralDomains as $domain) {
                Route::middleware('web')
                    ->domain($domain)
                    ->group(base_path('routes/web.php'));
            }

            // Route::middleware(['api'])
            //     // If you want to remove the default /api prefix,
            //     // you can also set apiPrefix to null in withRouting,
            //     // but defining the domain effectively overrides the need for the prefix on that domain
            //     ->group(base_path('routes/api.php'));


            // Route::middleware('web')->group(base_path('routes/tenant.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust Cloudflare IPs so Request::host() returns the tenant custom domain
        // (not the fallback origin). Required for stancl/tenancy domain identification.
        // IP list from https://www.cloudflare.com/ips/
        // $middleware->trustProxies(at: [
        //     '173.245.48.0/20',
        //     '103.21.244.0/22',
        //     '103.22.200.0/22',
        //     '103.31.4.0/22',
        //     '141.101.64.0/18',
        //     '108.162.192.0/18',
        //     '190.93.240.0/20',
        //     '188.114.96.0/20',
        //     '197.234.240.0/22',
        //     '198.41.128.0/17',
        //     '162.158.0.0/15',
        //     '104.16.0.0/13',
        //     '104.24.0.0/14',
        //     '172.64.0.0/13',
        //     '131.0.72.0/22',
        // ]);

        $middleware->alias([
            'module'    => \App\Http\Middleware\CheckTenantModule::class,
            'canonical' => \App\Http\Middleware\CanonicalDomain::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
