<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::with('role')->paginate(10);

        return view('user.index', [
            'users' => $users,
        ]);
    }

    public function create(Request $request): View
    {
        $roles = Role::all();

        return view('user.create', [
            'roles' => $roles,
        ]);
    }

    public function store(UserStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'] ?? null,
        ]);

        $request->session()->flash('user.id', $user->id);

        // Check if we should create another user
        if ($request->has('create_another')) {
            return redirect()->route('users.create')->with('success', 'User created successfully.');
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(Request $request, User $user): View
    {
        $user->load('role.permissions.feature');

        return view('user.show', [
            'user' => $user,
        ]);
    }

    public function edit(Request $request, User $user): View
    {
        $roles = Role::all();

        return view('user.edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'] ?? null,
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        $request->session()->flash('user.id', $user->id);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('users.index');
    }
}
