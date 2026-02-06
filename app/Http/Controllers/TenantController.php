<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Http\Requests\TenantStoreRequest;
use App\Http\Requests\TenantUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TenantController extends Controller
{
    /**
     * Display a listing of the tenants.
     */
    public function index(): View
    {
        $tenants = Tenant::with('domains')->paginate(15);

        return view('tenant.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new tenant.
     */
    public function create(): View
    {
        return view('tenant.create');
    }

    /**
     * Store a newly created tenant in storage.
     */
    public function store(TenantStoreRequest $request): RedirectResponse
    {
        $tenant = Tenant::create([
            'id' => $request->tenant_id,
            'name' => $request->name,
            'email' => $request->email,
            'description' => $request->description,
        ]);

        // Create domain for the tenant
        $tenant->domains()->create([
            'domain' => $request->domain,
        ]);

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant created successfully.');
    }

    /**
     * Display the specified tenant.
     */
    public function show(Tenant $tenant): View
    {
        $tenant->load('domains');

        return view('tenant.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified tenant.
     */
    public function edit(Tenant $tenant): View
    {
        $tenant->load('domains');

        return view('tenant.edit', compact('tenant'));
    }

    /**
     * Update the specified tenant in storage.
     */
    public function update(TenantUpdateRequest $request, Tenant $tenant): RedirectResponse
    {
        $tenant->update([
            'name' => $request->name,
            'email' => $request->email,
            'description' => $request->description,
        ]);

        // Update domain if provided
        if ($request->domain) {
            $domain = $tenant->domains()->first();
            if ($domain) {
                $domain->update(['domain' => $request->domain]);
            } else {
                $tenant->domains()->create(['domain' => $request->domain]);
            }
        }

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant updated successfully.');
    }

    /**
     * Remove the specified tenant from storage.
     */
    public function destroy(Tenant $tenant): RedirectResponse
    {
        $tenant->delete();

        return redirect()->route('tenants.index')
            ->with('success', 'Tenant deleted successfully.');
    }
}
