<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::create([
            'name' => 'Product',
            'version' => '1.0.0',
            'description' => 'Product module',
            'is_active' => true,
            'icon_path' => null,
            'price' => 0.00,
        ]);
    }
}
