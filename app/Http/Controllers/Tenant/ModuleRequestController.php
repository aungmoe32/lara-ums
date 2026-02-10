<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleRequestController extends Controller
{
    public function index()
    {
        $modules = Module::active()->get();
        $installedModules = tenant()->installed_modules ?? [];
        // dd($installedModules);

        // Fetch pending requests for this tenant
        $pendingRequests = ModuleRequest::where('tenant_id', tenant('id'))
            ->where('status', 'pending')
            ->pluck('module_name')
            ->toArray();

        // Fetch approved requests for this tenant
        $approvedRequests = ModuleRequest::where('tenant_id', tenant('id'))
            ->where('status', 'approved')
            ->pluck('module_name')
            ->toArray();

        return view('tenant.modules.index', compact('modules', 'installedModules', 'pendingRequests', 'approvedRequests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'module_name' => 'required|string|exists:mysql.modules,name',
        ]);

        $moduleName = $request->input('module_name');

        // Check if already installed
        $installedModules = tenant()->installed_modules ?? [];
        if (in_array($moduleName, $installedModules)) {
            return back()->with('error', 'Module is already installed.');
        }

        // Check if already requested
        $existingRequest = ModuleRequest::where('tenant_id', tenant('id'))
            ->where('module_name', $moduleName)
            ->whereIn('status', ['pending', 'approved']) // Include approved in check to prevent duplicate requests
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'Request already pending or approved for this module.');
        }

        ModuleRequest::create([
            'tenant_id' => tenant('id'),
            'module_name' => $moduleName,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Module request submitted successfully.');
    }

    public function install(Request $request)
    {
        $request->validate([
            'module_name' => 'required|string|exists:mysql.modules,name',
        ]);

        $moduleName = $request->input('module_name');
        $tenant = tenant();

        // Verify module is approved for this tenant
        $approved = ModuleRequest::where('tenant_id', $tenant->id)
            ->where('module_name', $moduleName)
            ->where('status', 'approved')
            ->exists();

        if (!$approved) {
            return back()->with('error', 'Module is not approved for installation.');
        }

        $installed = $tenant->installed_modules ?? [];
        if (in_array($moduleName, $installed)) {
            return back()->with('error', 'Module is already installed.');
        }

        $installed[] = $moduleName;
        // Update installed_modules if column or data attr exists
        $tenant->update(['installed_modules' => $installed]);

        // Dispatch job to install module
        \App\Jobs\InstallTenantModule::dispatch($tenant, $moduleName);

        return back()->with('success', "Module '{$moduleName}' installation job dispatched.");
    }
}
