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

        return back()->with('success', 'Module request approved. Tenant can now install the module.');
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
