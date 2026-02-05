<div class="bg-gray-900 text-white w-64 min-h-screen flex flex-col">
    <!-- App Logo -->
    {{-- <div class="p-4 text-3xl font-bold">
        <a href="{{ route('dashboard') }}" class="text-white">UMS</a>
    </div> --}}

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto">
        <nav class="mt-5 px-2">
            <!-- Users Section -->
            <div x-data="{ open: true }" class="mb-2">
                <button @click="open = !open"
                    class="w-full flex items-center space-x-2 py-2 px-4 text-white hover:bg-gray-800 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="flex-1 text-left">Users</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform" :class="{ 'rotate-180': open }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" class="pl-4">
                    @can('viewAny', App\Models\User::class)
                        <a href="{{ route('users.index') }}"
                            class="block py-2 px-4 text-sm text-gray-300 hover:bg-gray-800 rounded-md">User List</a>
                    @endcan
                    @can('create', App\Models\User::class)
                        <a href="{{ route('users.create') }}"
                            class="block py-2 px-4 text-sm text-gray-300 hover:bg-gray-800 rounded-md">Create User</a>
                    @endcan
                </div>
            </div>

            <!-- Roles Section -->
            <div x-data="{ open: true }" class="mb-2">
                <button @click="open = !open"
                    class="w-full flex items-center py-2 px-4 text-white hover:bg-gray-800 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span class="flex-1 text-left">Roles</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform" :class="{ 'rotate-180': open }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" class="pl-4">
                    @can('viewAny', App\Models\Role::class)
                        <a href="{{ route('roles.index') }}"
                            class="block py-2 px-4 text-sm text-gray-300 hover:bg-gray-800 rounded-md">Role List</a>
                    @endcan
                    @can('create', App\Models\Role::class)
                        <a href="{{ route('roles.create') }}"
                            class="block py-2 px-4 text-sm text-gray-300 hover:bg-gray-800 rounded-md">Create Role</a>
                    @endcan
                </div>
            </div>

            <!-- Tenants Section -->
            <div x-data="{ open: true }" class="mb-2">
                <button @click="open = !open"
                    class="w-full flex items-center py-2 px-4 text-white hover:bg-gray-800 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-2m-14 0h2m-2 0h-2m2 0V9a2 2 0 012-2h2a2 2 0 012 2v10"/>
                    </svg>
                    <span class="flex-1 text-left">Tenants</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform" :class="{ 'rotate-180': open }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" class="pl-4">
                    <a href="{{ route('tenants.index') }}"
                        class="block py-2 px-4 text-sm text-gray-300 hover:bg-gray-800 rounded-md">Tenant List</a>
                    <a href="{{ route('tenants.create') }}"
                        class="block py-2 px-4 text-sm text-gray-300 hover:bg-gray-800 rounded-md">Create Tenant</a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Bottom Links -->
    <div class="p-4 border-t border-gray-700">
        <a href="{{ route('profile.edit') }}"
            class="block py-2 px-4 text-sm text-gray-300 hover:bg-gray-800 rounded-md flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profile
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full text-left py-2 px-4 text-sm text-gray-300 hover:bg-gray-800 rounded-md flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Log Out
            </button>
        </form>
    </div>
</div>
