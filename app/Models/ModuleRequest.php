<?php

namespace App\Models;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;

class ModuleRequest extends Model
{
    protected $connection = 'mysql';

    protected $guarded = [];


    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
