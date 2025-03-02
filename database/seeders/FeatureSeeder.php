<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run()
    {
        Feature::create(['name' => 'User']);
        Feature::create(['name' => 'Product']);
    }
}
