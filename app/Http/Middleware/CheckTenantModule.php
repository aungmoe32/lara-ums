<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantModule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $moduleName): Response
    {
        $module = Module::find($moduleName);
        if (!$module || !$module->isEnabled()) {
            abort(404, "Module '{$moduleName}' not found or disabled globally.");
        }

        if (function_exists('tenant') && tenant()) {
            $tenant = tenant();
            $installedModules = $tenant->installed_modules ?? [];

            if (!in_array($moduleName, $installedModules)) {
                abort(403, "Access denied. You have not installed the '{$moduleName}' module.");
            }
        }

        return $next($request);
    }
}
