<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('tenants.show', $tenant) }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Tenant') }} - {{ $tenant->data['name'] ?? $tenant->id }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('tenants.update', $tenant) }}">
                        @csrf
                        @method('PUT')

                        <!-- Tenant ID (Read Only) -->
                        <div class="mb-4">
                            <x-input-label for="tenant_id_display" :value="__('Tenant ID')" />
                            <x-text-input id="tenant_id_display" class="block mt-1 w-full bg-gray-100 dark:bg-gray-700" 
                                         type="text" :value="$tenant->id" disabled />
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Tenant ID cannot be changed</p>
                        </div>

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Tenant Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" 
                                         :value="old('name', $tenant->data['name'] ?? '')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Contact Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" 
                                         :value="old('email', $tenant->data['email'] ?? '')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Domain -->
                        <div class="mb-4">
                            <x-input-label for="domain" :value="__('Domain')" />
                            <x-text-input id="domain" class="block mt-1 w-full" type="text" name="domain" 
                                         :value="old('domain', $tenant->domains->first()?->domain ?? '')" required />
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">The domain for this tenant (e.g., company.yourdomain.com)</p>
                            <x-input-error :messages="$errors->get('domain')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3" 
                                     class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                     placeholder="Optional description for this tenant">{{ old('description', $tenant->data['description'] ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex space-x-4">
                                <a href="{{ route('tenants.show', $tenant) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:bg-gray-400 dark:focus:bg-gray-500 active:bg-gray-500 dark:active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cancel
                                </a>
                                <x-primary-button>
                                    {{ __('Update Tenant') }}
                                </x-primary-button>
                            </div>
                            
                            <!-- Delete Button -->
                            <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit" 
                                                onclick="return confirm('Are you sure you want to delete this tenant? This action cannot be undone.')">
                                    Delete Tenant
                                </x-danger-button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>