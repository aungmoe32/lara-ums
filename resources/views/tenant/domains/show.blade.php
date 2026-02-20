<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Domain Setup') }}
            </h2>
            <a href="{{ route('domains.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Domains
            </a>
        </div>
    </x-slot>

    @php
    $fullyActive = $domain->status === 'active' && $domain->ssl_status === 'active';
    $fallback = config('services.cloudflare.fallback_origin', 'app.bartarpyan.site');
    @endphp

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash messages --}}
            @foreach (['success', 'error', 'info', 'warning'] as $msg)
            @if (session($msg))
            @php $c = ['success'=>'green','error'=>'red','info'=>'blue','warning'=>'yellow'][$msg]; @endphp
            <div class="bg-{{ $c }}-100 border border-{{ $c }}-400 text-{{ $c }}-700 px-4 py-3 rounded" role="alert">
                {{ session($msg) }}
            </div>
            @endif
            @endforeach

            {{-- Status overview card --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $domain->domain }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Added {{ $domain->created_at->format('F d, Y') }}</p>
                        </div>
                        @if ($fullyActive)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Live with SSL
                        </span>
                        @endif
                    </div>

                    {{-- Two-column status grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        {{-- Hostname routing status --}}
                        <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                                Hostname Routing
                            </p>
                            <div class="flex items-center justify-between">
                                <x-domain-status-badge :status="$domain->status" />
                                <span class="text-xs text-gray-400 dark:text-gray-500">result.status</span>
                            </div>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                @if ($domain->status === 'active')
                                Cloudflare is routing traffic for this hostname. ✓
                                @elseif ($domain->status === 'moved')
                                The CNAME is pointing elsewhere. Re-check your DNS records.
                                @else
                                Waiting for the CNAME to resolve to <code>{{ $fallback }}</code>.
                                @endif
                            </p>
                        </div>

                        {{-- SSL certificate status --}}
                        <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                                SSL Certificate
                            </p>
                            <div class="flex items-center justify-between">
                                <x-domain-status-badge :status="$domain->ssl_status" />
                                <span class="text-xs text-gray-400 dark:text-gray-500">result.ssl.status</span>
                            </div>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                @if ($domain->ssl_status === 'active')
                                SSL certificate is issued and valid. ✓
                                @elseif ($domain->ssl_status === 'pending_validation')
                                Cloudflare is validating the domain. This usually takes under 5 minutes after the CNAME resolves.
                                @elseif ($domain->ssl_status === 'pending_deployment')
                                Certificate issued — deploying to Cloudflare's edge. Almost there.
                                @else
                                SSL is in state: <strong>{{ $domain->ssl_status }}</strong>. Check status again in a few minutes.
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($fullyActive)
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800 dark:text-green-200">Domain is fully live!</h3>
                                <p class="mt-1 text-sm text-green-700 dark:text-green-300">
                                    <a href="https://{{ $domain->domain }}" target="_blank" class="underline font-medium">
                                        Visit https://{{ $domain->domain }} →
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if (!$fullyActive)
            {{-- Setup instructions --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">DNS Configuration</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Add the following <strong>CNAME record</strong> at your DNS registrar.
                        Cloudflare will detect it and issue the SSL certificate automatically — typically within 5 minutes.
                    </p>

                    {{-- CNAME instruction --}}
                    <div class="mb-8">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <span class="text-blue-600 dark:text-blue-300 font-semibold text-sm">1</span>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Add a CNAME record</h4>
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="grid grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Type</span>
                                            <p class="mt-1 font-mono text-gray-900 dark:text-gray-100">CNAME</p>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Name</span>
                                            <p class="mt-1 font-mono text-gray-900 dark:text-gray-100">
                                                @php
                                                $parts = explode('.', $domain->domain);
                                                echo count($parts) > 2 ? $parts[0] : '@';
                                                @endphp
                                            </p>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Value (Target)</span>
                                            <div class="mt-1 flex items-center">
                                                <code id="cname-value"
                                                    class="text-xs bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded text-gray-900 dark:text-gray-100">{{ $fallback }}</code>
                                                <button onclick="copyToClipboard('cname-value')" title="Copy"
                                                    class="ml-2 text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    If your domain is also on Cloudflare, set the proxy to <strong>DNS Only (grey cloud)</strong>.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Check status --}}
                    <div class="mb-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <span class="text-blue-600 dark:text-blue-300 font-semibold text-sm">2</span>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Check Status</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    After pointing the CNAME, click below to poll Cloudflare for both the
                                    <strong>hostname routing</strong> and <strong>SSL certificate</strong> statuses.
                                </p>
                                <form action="{{ route('domains.verify', $domain) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Check Status
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-yellow-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Good to know</h3>
                                <ul class="mt-2 text-sm text-yellow-700 dark:text-yellow-300 list-disc list-inside space-y-1">
                                    <li><strong>Hostname routing</strong> becomes <em>active</em> once Cloudflare detects the CNAME (usually &lt;5 min).</li>
                                    <li><strong>SSL status</strong> becomes <em>active</em> after the certificate is issued — requires the hostname to be active first.</li>
                                    <li>If SSL shows a rate-limit error, Cloudflare will retry automatically before the stated time.</li>
                                    <li>Apex domains (<code>example.com</code>) need ALIAS/ANAME support from your registrar.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        function copyToClipboard(id) {
            navigator.clipboard.writeText(document.getElementById(id).textContent.trim()).then(() => {
                const btn = document.getElementById(id).parentElement.querySelector('button');
                const orig = btn.innerHTML;
                btn.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>`;
                setTimeout(() => btn.innerHTML = orig, 2000);
            });
        }
    </script>
</x-app-layout>