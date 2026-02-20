<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanonicalDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = tenant();

        if (!$tenant) {
            return $next($request);
        }

        // Find the primary domain for this tenant
        $primaryDomain = $tenant->domains()->where('is_primary', true)->first();

        // No primary set — let the request through unchanged
        if (!$primaryDomain) {
            return $next($request);
        }

        $currentHost = $request->getHost();

        // Already on the primary domain — no redirect needed
        if ($currentHost === $primaryDomain->domain) {
            return $next($request);
        }

        // Not on the primary: build the canonical URL preserving path + query string
        $protocol  = $request->secure() ? 'https' : 'http';
        $targetUrl = $protocol . '://' . $primaryDomain->domain . $request->getRequestUri();

        // 301 = permanent redirect (SEO-friendly; tells Google which domain to index)
        return redirect($targetUrl, 301);
    }
}
