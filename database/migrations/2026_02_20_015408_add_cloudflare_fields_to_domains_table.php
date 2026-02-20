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
        Schema::table('domains', function (Blueprint $table) {
            // Cloudflare custom hostname ID (returned by API on creation)
            $table->string('cloudflare_id')->nullable()->after('tenant_id');
            // Mirrors Cloudflare statuses: pending_validation | active | moved | deleted
            $table->string('status')->default('pending_validation')->after('cloudflare_id');

            // Drop old Caddy/DNS-TXT verification columns
            $table->dropColumn(['verification_code', 'verified_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn(['cloudflare_id', 'status']);

            $table->string('verification_code')->nullable()->after('tenant_id');
            $table->timestamp('verified_at')->nullable()->after('verification_code');
        });
    }
};
