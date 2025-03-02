@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <x-auth-session-status class="mb-4" :status="session('success')" />
                    @endif
                    <h2 class="text-2xl font-semibold mb-6">Create Role</h2>

                    <form method="POST" action="{{ route('roles.store') }}">
                        @csrf

                        <!-- Role Name -->
                        <div class="mb-6">
                            <x-input-label for="name" :value="__('Role Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                placeholder="Enter your role" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Role Permissions -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Role Permissions</h3>

                            <div class="overflow-x-auto">
                                <table
                                    class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg">
                                    <thead>
                                        <tr>
                                            <th
                                                class="py-3 px-4 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700 w-1/3">
                                                Feature</th>
                                            <th
                                                class="py-3 px-4 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-b border-gray-300 dark:border-gray-700">
                                                Permissions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $features = \App\Models\Feature::with('permissions')->get();
                                        @endphp

                                        @foreach ($features as $feature)
                                            <tr class="border-b border-gray-300 dark:border-gray-700">
                                                <td class="py-4 px-4 text-sm">
                                                    <div class="mt-1 text-gray-700 dark:text-gray-300">{{ $feature->name }}
                                                    </div>
                                                </td>
                                                <td class="py-4 px-4">
                                                    <div class="flex flex-wrap gap-4">
                                                        <div class="flex items-center">
                                                            <input type="checkbox" id="select_all_{{ $feature->id }}"
                                                                class="select-all-feature mr-2 rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                                            <label for="select_all_{{ $feature->id }}"
                                                                class="text-gray-700 text-sm dark:text-gray-300 cursor-pointer">Select
                                                                All</label>
                                                        </div>
                                                        @foreach ($feature->permissions as $permission)
                                                            <div class="flex items-center">
                                                                <input type="checkbox" id="permission_{{ $permission->id }}"
                                                                    name="permissions[]" value="{{ $permission->id }}"
                                                                    class="feature-{{ $feature->id }}-permission rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                                                <label for="permission_{{ $permission->id }}"
                                                                    class="ml-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">{{ ucfirst($permission->name) }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                {{ __('Create') }}
                            </x-primary-button>

                            <x-secondary-button onclick="window.location='{{ route('roles.index') }}'" type="button">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <x-secondary-button type="submit" name="create_another" value="1">
                                {{ __('Create & Create Another') }}
                            </x-secondary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript to handle "Select All" functionality
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckboxes = document.querySelectorAll('.select-all-feature');

            selectAllCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const featureId = this.id.split('_')[2];
                    const featurePermissions = document.querySelectorAll(
                        `.feature-${featureId}-permission`);

                    featurePermissions.forEach(permissionCheckbox => {
                        permissionCheckbox.checked = this.checked;
                    });
                });
            });
        });
    </script>
@endsection
