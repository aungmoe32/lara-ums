<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends \Stancl\Tenancy\Database\Models\Domain
{
    protected $fillable = [
        'domain',
        'tenant_id',
        'verified_at',
        'verification_code'
    ];
    protected $casts = [
        'verified_at' => 'datetime',
    ];
}
