<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary(); // Tenant UUID (e.g., 'foo')

            // Core Tenancy Data
            $table->json('data')->nullable(); // Additional metadata

            // Stores: ['Blog', 'Shop'] or {'Blog': {'version': '1.1', 'installed_at': '...'}}
            $table->json('installed_modules')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
