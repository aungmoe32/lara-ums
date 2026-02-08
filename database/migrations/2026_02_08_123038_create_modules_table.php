<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'Blog', 'Ecommerce'
            $table->string('version')->default('1.0.0');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(false); // Global "Kill Switch"
            $table->string('icon_path')->nullable(); // For the UI
            $table->decimal('price', 8, 2)->default(0.00); // If you charge for modules
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
