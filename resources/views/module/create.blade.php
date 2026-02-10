<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Upload Module') }}
            </h2>
            <a href="{{ route('modules.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Modules
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('modules.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            {{-- <h3 class="text-lg font-semibold mb-4">Module Upload Instructions</h3>
                            <div
                                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                                <ul class="list-disc list-inside space-y-2 text-sm text-blue-900 dark:text-blue-200">
                                    <li>Upload a valid module ZIP file containing a <code
                                            class="bg-blue-100 dark:bg-blue-800 px-1 rounded">module.json</code> file
                                    </li>
                                    <li>Maximum file size: 50MB</li>
                                    <li>The module will be extracted to the <code
                                            class="bg-blue-100 dark:bg-blue-800 px-1 rounded">Modules/</code> directory
                                    </li>
                                    <li>Module will be disabled by default after upload</li>
                                </ul>
                            </div> --}}

                            {{-- <div
                                class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">
                                <h4 class="font-semibold text-yellow-900 dark:text-yellow-200 mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Security Warning
                                </h4>
                                <p class="text-sm text-yellow-900 dark:text-yellow-200">
                                    Only upload modules from trusted sources. Malicious modules can compromise your
                                    system.
                                </p>
                            </div> --}}
                        </div>

                        <div class="mb-6">
                            <label for="module_file"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Module ZIP File <span class="text-red-500">*</span>
                            </label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md hover:border-blue-400 dark:hover:border-blue-500 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="module_file"
                                            class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a file</span>
                                            <input id="module_file" name="module_file" type="file" class="sr-only"
                                                accept=".zip" required>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        ZIP files up to 50MB
                                    </p>
                                </div>
                            </div>
                            @error('module_file')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('modules.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Upload Module
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Module Structure Example -->
            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Expected Module Structure</h3>
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 font-mono text-sm">
                        <pre class="text-gray-800 dark:text-gray-200">ModuleName/
├── module.json          <span class="text-green-600 dark:text-green-400">(Required)</span>
├── Config/
│   └── config.php
├── Database/
│   ├── Migrations/
│   └── Seeders/
├── Http/
│   └── Controllers/
├── Resources/
│   └── views/
└── Routes/
    └── web.php</pre>
                    </div>

                    <div class="mt-4 bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                        <h4 class="font-semibold mb-2">Sample module.json:</h4>
                        <pre class="text-sm text-gray-800 dark:text-gray-200 overflow-x-auto"><code>{
  "name": "Blog",
  "version": "1.0.0",
  "description": "Blog management module",
  "price": 0.00,
  "icon": "path/to/icon.png"
}</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // File input display
        const fileInput = document.getElementById('module_file');
        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const label = document.querySelector('label[for="module_file"] span');
                label.textContent = fileName;
            }
        });
    </script>
</x-app-layout>