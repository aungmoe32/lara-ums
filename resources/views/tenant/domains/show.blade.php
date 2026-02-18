<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Domain Setup') }}
            </h2>
            <a href="{{ route('domains.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Domains
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            @if (session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('info') }}</span>
            </div>
            @endif

            <!-- Domain Status Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $domain->domain }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Added on {{ $domain->created_at->format('F d, Y') }}
                            </p>
                        </div>
                        <div>
                            @if ($domain->verified_at)
                            <span
                                class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Verified
                            </span>
                            @else
                            <span
                                class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                        clip-rule="evenodd" />
                                </svg>
                                Pending Verification
                            </span>
                            @endif
                        </div>
                    </div>

                    @if ($domain->verified_at)
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                                    Domain is Live!
                                </h3>
                                <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                                    <p>Your domain was verified on {{ $domain->verified_at->format('F d, Y \a\t g:i A') }}.
                                        It's now active with automatic SSL!</p>
                                    <p class="mt-2">
                                        <a href="https://{{ $domain->domain }}" target="_blank"
                                            class="font-medium underline hover:text-green-600">
                                            Visit {{ $domain->domain }} →
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if (!$domain->verified_at)
            <!-- DNS Configuration Steps -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        DNS Configuration Required
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        To verify ownership and activate your custom domain, please add the following DNS records to
                        your domain registrar (GoDaddy, Namecheap, Cloudflare, etc.):
                    </p>

                    <!-- Step 1: TXT Record -->
                    <div class="mb-6">
                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <span class="text-blue-600 dark:text-blue-300 font-semibold">1</span>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                    Add TXT Record (Verification)
                                </h4>
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="grid grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Type:</span>
                                            <p class="mt-1 text-gray-900 dark:text-gray-100">TXT</p>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Name:</span>
                                            <p class="mt-1 text-gray-900 dark:text-gray-100">@ (or leave blank)</p>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Value:</span>
                                            <div class="mt-1 flex items-center">
                                                <code id="txt-value"
                                                    class="text-xs bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded text-gray-900 dark:text-gray-100 break-all">{{ $domain->verification_code }}</code>
                                                <button onclick="copyToClipboard('txt-value')"
                                                    class="ml-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: A Record -->
                    <div class="mb-6">
                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <span class="text-blue-600 dark:text-blue-300 font-semibold">2</span>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                    Add A Record (Points to our server)
                                </h4>
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="grid grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Type:</span>
                                            <p class="mt-1 text-gray-900 dark:text-gray-100">A</p>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Name:</span>
                                            <p class="mt-1 text-gray-900 dark:text-gray-100">@ (or leave blank)</p>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Value:</span>
                                            <div class="mt-1 flex items-center">
                                                <code id="a-record"
                                                    class="text-xs bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded text-gray-900 dark:text-gray-100">{{ config('app.server_ip', 'YOUR_SERVER_IP') }}</code>
                                                <button onclick="copyToClipboard('a-record')"
                                                    class="ml-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Verify -->
                    <div class="mb-6">
                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <span class="text-blue-600 dark:text-blue-300 font-semibold">3</span>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                    Verify Domain
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    After adding both DNS records, click the button below to verify. DNS changes can
                                    take up to 48 hours to propagate.
                                </p>
                                <form action="{{ route('domains.verify', $domain) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Verify Domain
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Help Section -->
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                    Important Notes
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>DNS changes can take anywhere from a few minutes to 48 hours to propagate</li>
                                        <li>Make sure to disable any proxy/CDN (like Cloudflare's orange cloud) initially</li>
                                        <li>Once verified, SSL certificates will be automatically issued by Let's Encrypt</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent;
            navigator.clipboard.writeText(text).then(() => {
                // Show a temporary success message
                const originalText = element.parentElement.querySelector('button').innerHTML;
                element.parentElement.querySelector('button').innerHTML = `
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                `;
                setTimeout(() => {
                    element.parentElement.querySelector('button').innerHTML = originalText;
                }, 2000);
            });
        }
    </script>
</x-app-layout>