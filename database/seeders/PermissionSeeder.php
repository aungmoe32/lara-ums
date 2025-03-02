<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Get features using Eloquent
        $features = \App\Models\Feature::all();

        // Define permission types
        $permissionTypes = ['create', 'read', 'update', 'delete'];

        // Create permissions for each feature
        foreach ($features as $feature) {
            foreach ($permissionTypes as $permissionType) {
                Permission::create([
                    'name' => $permissionType,
                    'feature_id' => $feature->id
                ]);
            }
        }
    }
}
