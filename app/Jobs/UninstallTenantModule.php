<?php

namespace App\Jobs;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class UninstallTenantModule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Tenant $tenant,
        protected string $moduleName
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            tenancy()->initialize($this->tenant);

            // Rollback all migrations for this module (Reset)
            // Artisan::call('module:migrate-reset', [
            //     'module' => $this->moduleName,
            //     '--force' => true
            // ]);

            // Clear optimization cache
            Artisan::call('optimize:clear');

            Log::info("Module '{$this->moduleName}' uninstalled successfully for tenant {$this->tenant->id}.");

        } catch (\Exception $e) {
            Log::error("Failed to uninstall module {$this->moduleName} for tenant {$this->tenant->id}: " . $e->getMessage());
            throw $e;
        } finally {
            // End Tenancy to clean up scope
            tenancy()->end();
        }
    }
}
