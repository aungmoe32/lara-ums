{{--
    @extends('layouts.app')

    @section('content')
        user.show template
    @endsection
--}}

@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">User Details</h2>
                        <div class="flex space-x-2">
                            @can('update', $user)
                                <a href="{{ route('users.edit', $user) }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Edit
                                </a>
                            @endcan
                            <x-secondary-button onclick="window.location='{{ route('users.index') }}'" type="button">
                                {{ __('Back to List') }}
                            </x-secondary-button>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">ID</h3>
                                <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $user->id }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</h3>
                                <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</h3>
                                <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $user->email }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</h3>
                                <p class="mt-1">
                                    @if ($user->role)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                            {{ $user->role->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">No role assigned</span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</h3>
                                <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">
                                    {{ $user->created_at->format('M d, Y H:i') }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</h3>
                                <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">
                                    {{ $user->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($user->role)
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">User Permissions (via
                                Role)</h3>

                            @if ($user->role->permissions->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @php
                                        $permissionsByFeature = $user->role->permissions->groupBy(function (
                                            $permission,
                                        ) {
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
                                <p class="text-gray-500 dark:text-gray-400">No permissions assigned to this user's role.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
