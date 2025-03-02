@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Role Details</h2>
                        <div class="flex space-x-2">
                            @can('update', $role)
                                <a href="{{ route('roles.edit', $role) }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Edit
                                </a>
                            @endcan
                            <x-secondary-button onclick="window.location='{{ route('roles.index') }}'" type="button">
                                {{ __('Back to List') }}
                            </x-secondary-button>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">ID</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $role->id }}</p>
                        </div>

                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $role->name }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Permissions</h3>

                            @if ($role->permissions->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @php
                                        $permissionsByFeature = $role->permissions->groupBy(function ($permission) {
                                            return $permission->feature->name;
                                        });
                                    @endphp

                                    @foreach ($permissionsByFeature as $featureName => $permissions)
                                        <div
                                            class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                            <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                {{ $featureName }}</h4>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($permissions as $permission)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                        {{ ucfirst($permission->name) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400">No permissions assigned to this role.</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">Users with this Role</h3>

                        @if ($role->users->count() > 0)
                            <div class="overflow-x-auto">
                                <table
                                    class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg">
                                    <thead>
                                        <tr>
                                            <th
                                                class="py-3 px-4 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700">
                                                ID</th>
                                            <th
                                                class="py-3 px-4 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700">
                                                Name</th>
                                            <th
                                                class="py-3 px-4 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700">
                                                Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($role->users as $user)
                                            <tr class="border-b border-gray-300 dark:border-gray-700">
                                                <td class="py-4 px-4 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $user->id }}</td>
                                                <td class="py-4 px-4 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $user->name }}</td>
                                                <td class="py-4 px-4 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $user->email }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">No users have been assigned this role.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
