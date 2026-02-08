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
            'name' => 'Blog',
            'version' => '1.0.0',
            'description' => 'Blog module',
            'is_active' => true,
            'icon_path' => 'blog.png',
            'price' => 0.00,
        ]);
    }
}
