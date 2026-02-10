<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Available Modules') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if($modules->isEmpty())
                        <p class="text-center text-gray-500">No modules available at the moment.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($modules as $module)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 flex flex-col h-full hover:shadow-lg transition-shadow duration-200">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center">
                                            @if($module->icon_path)
                                                <img src="{{ asset($module->icon_path) }}" alt="{{ $module->name }}" class="w-10 h-10 mr-3">
                                            @else
                                                <div class="w-12 mr-3 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                                    {{ substr($module->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <h3 class="text-lg font-semibold">{{ $module->name }}</h3>
                                        </div>
                                        <span class="text-sm text-gray-500">v{{ $module->version }}</span>
                                    </div>
                                    
                                    <p class="text-gray-600 dark:text-gray-400 mb-4 flex-grow">
                                        {{ $module->description }}
                                    </p>
                                    
                                    <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100 dark:border-gray-700">
                                        <span class="font-bold text-lg">
                                            @if($module->price > 0)
                                                ${{ number_format($module->price, 2) }}
                                            @else
                                                Free
                                            @endif
                                        </span>

                                        @if(in_array($module->name, $installedModules))
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                                Installed
                                            </span>
                                        @elseif(in_array($module->name, $pendingRequests))
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                                Pending Approval
                                            </span>
                                        @elseif(in_array($module->name, $approvedRequests))
                                            <form action="{{ route('tenant.modules.install') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="module_name" value="{{ $module->name }}">
                                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-sm font-medium">
                                                    Install
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('tenant.modules.request') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="module_name" value="{{ $module->name }}">
                                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">
                                                    Request Module
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
