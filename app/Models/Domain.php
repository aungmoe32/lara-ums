<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends \Stancl\Tenancy\Database\Models\Domain
{
    protected $fillable = [
        'domain',
        'tenant_id',
        'cloudflare_id',
        'status',      // result.status       — hostname routing (active, moved...)
        'ssl_status',  // result.ssl.status   — SSL cert (pending_validation, active...)
    ];
}
