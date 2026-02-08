<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ModuleController extends Controller
{
    public function index(): View
    {
        $modules = Module::orderBy('name')->paginate(15);

        return view('module.index', compact('modules'));
    }

    public function toggleStatus(Module $module): RedirectResponse
    {
        $module->is_active = !$module->is_active;
        $module->save();

        $status = $module->is_active ? 'enabled' : 'disabled';

        return redirect()->route('modules.index')
            ->with('success', "Module '{$module->name}' has been {$status} successfully.");
    }
}
