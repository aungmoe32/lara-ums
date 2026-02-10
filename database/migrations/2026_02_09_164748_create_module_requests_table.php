<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('module_requests', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id'); // Link to tenants table
            $table->string('module_name'); // e.g., 'Blog'
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->timestamps();
            // prevent duplicate pending requests for same module
            $table->unique(['tenant_id', 'module_name', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_requests');
    }
};
