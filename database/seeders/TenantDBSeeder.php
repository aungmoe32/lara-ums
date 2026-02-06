<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Feature;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class TenantDBSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // Create roles
        $adminRole = Role::create([
            'name' => 'Admin',
        ]);

        $userRole = Role::create([
            'name' => 'User',
        ]);

        $saleRole = Role::create([
            'name' => 'Sale',
        ]);

        // Create users with assigned roles
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role_id' => $adminRole->id,
        ]);

        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'role_id' => $userRole->id,
        ]);

        User::factory()->create([
            'name' => 'Sales User',
            'email' => 'sales@example.com',
            'role_id' => $saleRole->id,
        ]);

        $this->call([
            FeatureSeeder::class,
            PermissionSeeder::class,
        ]);

        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $adminRole->permissions()->attach($permission->id);
        }
        // Assign read permissions to User role
        $readPermissions = Permission::where('name', 'like', '%read%')->get();
        $userRole->permissions()->sync($readPermissions->pluck('id'));
        // Get read permissions and product create permission for sales role
        $salesPermissions = Permission::where(function ($query) {
            $query->where('name', 'read')
                ->orWhere(function ($query) {
                    $query->where('name', 'create')
                        ->whereHas('feature', function ($query) {
                            $query->where('name', 'Product');
                        });
                });
        })->get();

        $saleRole->permissions()->sync($salesPermissions->pluck('id'));
    }
}
