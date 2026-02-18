<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Stancl\Tenancy\Facades\Tenancy;
use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
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
     * Store a newly created domain in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'domain' => [
                'required',
                'string',
                'regex:/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i',
                'unique:mysql.domains,domain'
            ]
        ], [
            'domain.regex' => 'Please enter a valid domain name (e.g., shop.example.com)',
            'domain.unique' => 'This domain is already registered in the system.'
        ]);

        $tenant = tenant();

        $domain = Tenancy::central(function () use ($request, $tenant) {
            // Create Domain Record (Unverified)
            $domain = $tenant->domains()->create([
                'domain' => strtolower($request->domain),
                'verification_code' => 'lara-ums-verification=' . Str::random(32),
            ]);
            return $domain;
        });

        return redirect()
            ->route('domains.show', $domain)
            ->with('success', 'Domain added! Please verify ownership by adding the DNS records.');
    }

    /**
     * Display the specified domain.
     */
    public function show(Domain $domain)
    {
        // Ensure the domain belongs to the current tenant
        if ($domain->tenant_id !== tenant()->id) {
            abort(403, 'Unauthorized access to this domain.');
        }

        return view('tenant.domains.show', compact('domain'));
    }

    /**
     * Verify domain ownership via DNS TXT record.
     */
    public function verify(Domain $domain)
    {
        // Ensure the domain belongs to the current tenant
        if ($domain->tenant_id !== tenant()->id) {
            abort(403, 'Unauthorized access to this domain.');
        }

        // Check if already verified
        if ($domain->verified_at) {
            return back()->with('info', 'This domain is already verified.');
        }

        // Check DNS Records
        $records = @dns_get_record($domain->domain, DNS_TXT);
        $verified = false;

        if ($records) {
            foreach ($records as $record) {
                if (isset($record['txt']) && $record['txt'] === $domain->verification_code) {
                    $verified = true;
                    break;
                }
            }
        }

        if ($verified) {
            $domain->update(['verified_at' => now()]);
            return back()->with('success', 'Domain verified successfully! It will be live shortly.');
        }

        return back()->with('error', 'TXT record not found. DNS changes can take up to 48 hours to propagate. Please try again later.');
    }

    public function destroy(Domain $domain)
    {
        // Ensure the domain belongs to the current tenant
        if ($domain->tenant_id !== tenant()->id) {
            abort(403, 'Unauthorized access to this domain.');
        }

        // Prevent deletion of the primary domain (subdomain)
        if ($domain->domain === tenant()->id . '.' . config('tenancy.central_domains.0')) {
            return back()->with('error', 'Cannot delete your primary subdomain.');
        }

        $domain->delete();

        return redirect()
            ->route('domains.index')
            ->with('success', 'Domain removed successfully.');
    }
}
