<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\CloudflareService;
use Illuminate\Http\Request;
use Stancl\Tenancy\Facades\Tenancy;
use App\Models\Domain;

class DomainController extends Controller
{
    public function __construct(protected CloudflareService $cloudflare) {}

    /**
     * Display a listing of the tenant's domains.
     */
    public function index()
    {
        $domains = tenant()->domains()->get();

        return view('tenant.domains.index', compact('domains'));
    }

    /**
     * Show the form for creating a new domain.
     */
    public function create()
    {
        return view('tenant.domains.create');
    }

    /**
     * Register the domain in the database and with Cloudflare for SaaS.
     */
    public function store(Request $request)
    {
        $request->validate([
            'domain' => [
                'required',
                'string',
                'regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i',
                'unique:mysql.domains,domain',
            ],
        ], [
            'domain.regex'  => 'Please enter a valid domain name (e.g., shop.example.com)',
            'domain.unique' => 'This domain is already registered in the system.',
        ]);

        $tenant     = tenant();
        $domainName = strtolower($request->domain);

        // 1. Register with Cloudflare for SaaS
        $result = $this->cloudflare->createHostname($domainName);

        if (empty($result['success'])) {
            $errorMessage = $result['errors'][0]['message'] ?? 'Unknown Cloudflare error.';
            return back()->withInput()->withErrors(['domain' => 'Cloudflare Error: ' . $errorMessage]);
        }

        // 2. Parse both statuses from the response
        $hostStatus = $result['result']['status']        ?? 'pending_validation';
        $sslStatus  = $result['result']['ssl']['status'] ?? 'pending_validation';

        // 3. Persist to the central DB.
        //    Automatically set as primary if this is the tenant's first custom domain.
        $domain = Tenancy::central(function () use ($domainName, $tenant, $result, $hostStatus, $sslStatus) {
            $isFirst = $tenant->domains()->count() === 0;

            return $tenant->domains()->create([
                'domain'        => $domainName,
                'cloudflare_id' => $result['result']['id'],
                'status'        => $hostStatus,
                'ssl_status'    => $sslStatus,
                'is_primary'    => $isFirst, // first domain is always primary
            ]);
        });

        return redirect()
            ->route('domains.show', $domain)
            ->with('success', 'Domain added! Please point a CNAME record to ' . config('services.cloudflare.fallback_origin') . '.');
    }

    /**
     * Display the specified domain with setup instructions.
     */
    public function show(Domain $domain)
    {
        $this->authorizeDomain($domain);

        return view('tenant.domains.show', compact('domain'));
    }

    /**
     * Poll Cloudflare for the latest hostname + SSL statuses and sync to DB.
     *
     * A domain is fully live only when BOTH:
     *   result.status     === 'active'   (Cloudflare is routing traffic)
     *   result.ssl.status === 'active'   (SSL cert is issued and valid)
     */
    public function verify(Domain $domain)
    {
        $this->authorizeDomain($domain);

        if ($domain->isFullyActive()) {
            return back()->with('info', 'This domain is already active with SSL.');
        }

        if (!$domain->cloudflare_id) {
            return back()->with('error', 'No Cloudflare record found for this domain. Please re-add it.');
        }

        $result = $this->cloudflare->getHostname($domain->cloudflare_id);

        if (empty($result['success'])) {
            return back()->with('error', 'Could not reach Cloudflare. Please try again later.');
        }

        $hostStatus = $result['result']['status']        ?? 'pending_validation';
        $sslStatus  = $result['result']['ssl']['status'] ?? 'pending_validation';

        $domain->update([
            'status'     => $hostStatus,
            'ssl_status' => $sslStatus,
        ]);

        // Fully live: both hostname and SSL are active
        if ($hostStatus === 'active' && $sslStatus === 'active') {
            return back()->with('success', 'Domain is active and SSL is live!');
        }

        // SSL pending but hostname already routed — most common transient state
        if ($hostStatus === 'active' && $sslStatus !== 'active') {
            $sslErrors = collect($result['result']['ssl']['validation_errors'] ?? [])
                ->pluck('message')
                ->implode(' ');

            $hint = $sslErrors
                ? "SSL note: {$sslErrors}"
                : "Cloudflare is still issuing the SSL certificate. Check back in a few minutes.";

            return back()->with('warning', "Hostname is routed — {$hint}");
        }

        return back()->with('warning', "Hostname status: '{$hostStatus}', SSL status: '{$sslStatus}'. Ensure your CNAME points to " . config('services.cloudflare.fallback_origin') . ".");
    }

    /**
     * Set this domain as the tenant's primary (canonical) domain.
     *
     * Enforces the "Highlander Rule": only one domain can be primary.
     * All other domains are demoted automatically.
     */
    public function setPrimary(Domain $domain)
    {
        $this->authorizeDomain($domain);

        // Only a fully active domain can be set as primary
        if (!$domain->isFullyActive()) {
            return back()->with('error', 'Only a domain with active hostname and SSL can be set as primary.');
        }

        Tenancy::central(function () use ($domain) {
            $tenantId = $domain->tenant_id;

            // 1. Demote ALL domains of this tenant
            Domain::where('tenant_id', $tenantId)->update(['is_primary' => false]);

            // 2. Promote only this one
            $domain->update(['is_primary' => true]);
        });

        return redirect()
            ->route('domains.index')
            ->with('success', "'{$domain->domain}' is now your primary domain. All other domains will redirect here.");
    }

    /**
     * Remove the domain from both the DB and Cloudflare.
     */
    public function destroy(Domain $domain)
    {
        $this->authorizeDomain($domain);

        if ($domain->is_primary) {
            return back()->with('error', 'Cannot delete the primary domain. Set another domain as primary first.');
        }

        if ($domain->domain === tenant()->id . '.' . config('tenancy.central_domains.0')) {
            return back()->with('error', 'Cannot delete your primary subdomain.');
        }

        // Remove from Cloudflare first
        if ($domain->cloudflare_id) {
            $this->cloudflare->deleteHostname($domain->cloudflare_id);
        }

        $domain->delete();

        return redirect()
            ->route('domains.index')
            ->with('success', 'Domain removed successfully.');
    }

    /**
     * Domain is fully live when BOTH the hostname routing AND the SSL cert are active.
     * Delegates to the model helper.
     */

    /**
     * Ensure the domain belongs to the currently authenticated tenant.
     */
    private function authorizeDomain(Domain $domain): void
    {
        if ($domain->tenant_id !== tenant()->id) {
            abort(403, 'Unauthorized access to this domain.');
        }
    }
}
