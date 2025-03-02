@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Roles</h2>
                        <a href="{{ route('roles.create') }}"
                            class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded">
                            Create Role
                        </a>
                    </div>

                    @if (session('success'))
                        <x-auth-session-status class="mb-4" :status="session('success')" />
                    @endif

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
                                        Permissions</th>
                                    <th
                                        class="py-3 px-4 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr class="border-b border-gray-300 dark:border-gray-700">
                                        <td class="py-4 px-4 text-sm text-gray-700 dark:text-gray-300">{{ $role->id }}
                                        </td>
                                        <td class="py-4 px-4 text-sm text-gray-700 dark:text-gray-300">{{ $role->name }}
                                        </td>
                                        <td class="py-4 px-4 text-sm text-gray-700 dark:text-gray-300">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($role->permissions as $permission)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                                        {{ $permission->feature->name }}: {{ ucfirst($permission->name) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-sm">
                                            <div class="flex space-x-2">
                                                @can('view', $role)
                                                    <a href="{{ route('roles.show', $role) }}"
                                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">View</a>
                                                @endcan
                                                @can('update', $role)
                                                    <a href="{{ route('roles.edit', $role) }}"
                                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</a>
                                                @endcan
                                                @can('delete', $role)
                                                    <form method="POST" action="{{ route('roles.destroy', $role) }}"
                                                        class="inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this role?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="py-4 px-4 text-sm text-gray-500 dark:text-gray-400 text-center">No roles
                                            found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
