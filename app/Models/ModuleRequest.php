<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Models\Tenant;

class ModuleRequest extends Model
{
    protected $connection = 'mysql';

    protected $guarded = [];


    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
