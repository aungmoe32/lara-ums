<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class, 'role');
    }

    public function index(Request $request): View
    {
        $roles = Role::with('permissions.feature')->get();

        return view('role.index', [
            'roles' => $roles,
        ]);
    }

    public function create(Request $request): View
    {
        $features = \App\Models\Feature::with('permissions')->get();

        return view('role.create', [
            'features' => $features,
        ]);
    }

    public function store(RoleStoreRequest $request): RedirectResponse
    {
        $role = Role::create($request->validated());

        // Attach permissions if any are selected
        if ($request->has('permissions')) {
            $role->permissions()->attach($request->permissions);
        }

        $request->session()->flash('role.id', $role->id);

        // Check if we should create another role
        if ($request->has('create_another')) {
            return redirect()->route('roles.create')->with('success', 'Role created successfully.');
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function show(Request $request, Role $role): View
    {
        $role->load(['permissions.feature', 'users']);

        return view('role.show', [
            'role' => $role,
        ]);
    }

    public function edit(Request $request, Role $role): View
    {
        $features = \App\Models\Feature::with('permissions')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('role.edit', [
            'role' => $role,
            'features' => $features,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    public function update(RoleUpdateRequest $request, Role $role): RedirectResponse
    {
        $role->update($request->validated());

        // Sync permissions
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->detach();
        }

        $request->session()->flash('role.id', $role->id);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Request $request, Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('roles.index');
    }
}
