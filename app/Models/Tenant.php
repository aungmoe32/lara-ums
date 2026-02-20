<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /**
     * Returns the tenant's primary domain string, falling back to the first
     * domain if none is explicitly marked primary.
     *
     * Usage in views / jobs: tenant()->primary_domain
     */
    public function getPrimaryDomainAttribute(): ?string
    {
        $primary = $this->domains()->where('is_primary', true)->first();

        if ($primary) {
            return $primary->domain;
        }

        // Fallback: first domain in the list
        return $this->domains()->first()?->domain;
    }
}
