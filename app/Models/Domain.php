<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Domain extends \Stancl\Tenancy\Database\Models\Domain
{
    protected $fillable = [
        'domain',
        'tenant_id',
        'cloudflare_id',
        'status',      // result.status     — hostname routing (active, moved...)
        'ssl_status',  // result.ssl.status — SSL cert (pending_validation, active...)
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // ---------------------------------------------------------------------------
    // Scopes
    // ---------------------------------------------------------------------------

    /** Returns only the primary domain for a tenant. */
    public function scopePrimary(Builder $query): Builder
    {
        return $query->where('is_primary', true);
    }

    /** Returns only verified (fully live) domains. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')->where('ssl_status', 'active');
    }

    // ---------------------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------------------

    /** True when Cloudflare has both hostname routing and SSL active. */
    public function isFullyActive(): bool
    {
        return $this->status === 'active' && $this->ssl_status === 'active';
    }
}
