<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ModuleRequest;
use App\Models\Tenant;
use Illuminate\Http\Request;

class ModuleRequestController extends Controller
{
    public function index()
    {
        $requests = ModuleRequest::with('tenant')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('central.requests.index', compact('requests'));
    }

    public function approve(ModuleRequest $moduleRequest)
    {
        if ($moduleRequest->status !== 'pending') {
            return back()->with('error', 'Request is not pending.');
        }

        $moduleRequest->update(['status' => 'approved']);

        // Add to Tenant's installed_modules data column
        $tenant = $moduleRequest->tenant;
        if ($tenant) {
            $installed = $tenant->installed_modules ?? [];
            if (!in_array($moduleRequest->module_name, $installed)) {
                $installed[] = $moduleRequest->module_name;
                $tenant->update(['installed_modules' => $installed]);
            }
        }

        // Dispatch job to run migrations/seeders for this module on this tenant
        // InstallTenantModule::dispatch($tenant, $moduleRequest->module_name);

        return back()->with('success', 'Module request approved and module added to tenant.');
    }

    public function reject(ModuleRequest $moduleRequest)
    {
        if ($moduleRequest->status !== 'pending') {
            return back()->with('error', 'Request is not pending.');
        }

        $moduleRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Module request rejected.');
    }
}
