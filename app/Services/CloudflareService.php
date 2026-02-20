<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudflareService
{
    protected string $baseUrl = 'https://api.cloudflare.com/client/v4';
    protected ?string $zoneId;
    protected ?string $apiToken;

    public function __construct()
    {
        $this->zoneId    = config('services.cloudflare.zone_id');
        $this->apiToken  = config('services.cloudflare.api_token');
    }

    /**
     * Register a new custom hostname with Cloudflare for SaaS.
     * Returns the Cloudflare API response array.
     *
     * ssl.method = 'http'  => tenant just needs to point the CNAME; CF issues cert automatically.
     * ssl.method = 'txt'   => CF provides a TXT record for the tenant to add first.
     */
    public function createHostname(string $domain): array
    {
        $response = Http::withToken($this->apiToken)
            ->post("{$this->baseUrl}/zones/{$this->zoneId}/custom_hostnames", [
                'hostname' => $domain,
                'ssl'      => [
                    'method'   => 'http',   // simplest UX: just point the CNAME
                    'type'     => 'dv',
                    'settings' => ['min_tls_version' => '1.2'],
                ],
            ]);

        if (!$response->successful()) {
            Log::error('Cloudflare createHostname failed', [
                'domain'   => $domain,
                'status'   => $response->status(),
                'body'     => $response->body(),
            ]);
        }

        return $response->json();
    }

    /**
     * Fetch the current status of a custom hostname from Cloudflare.
     */
    public function getHostname(string $cloudflareId): array
    {
        $response = Http::withToken($this->apiToken)
            ->get("{$this->baseUrl}/zones/{$this->zoneId}/custom_hostnames/{$cloudflareId}");

        return $response->json();
    }

    /**
     * Delete a custom hostname from Cloudflare.
     */
    public function deleteHostname(string $cloudflareId): bool
    {
        $response = Http::withToken($this->apiToken)
            ->delete("{$this->baseUrl}/zones/{$this->zoneId}/custom_hostnames/{$cloudflareId}");

        if (!$response->successful()) {
            Log::error('Cloudflare deleteHostname failed', [
                'cloudflare_id' => $cloudflareId,
                'status'        => $response->status(),
                'body'          => $response->body(),
            ]);
        }

        return $response->successful();
    }
}
