<?php

namespace App\Jobs;

use App\Models\ModuleActivation;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Nwidart\Modules\Facades\Module as NwidartModule;
use Stancl\Tenancy\Facades\GlobalCache;

class InstallTenantModule implements ShouldQueue
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
            $moduleName = $this->moduleName;
            $module = NwidartModule::find($moduleName);

            if (!$module) {
                Log::error("Module '{$moduleName}' not found on filesystem.");
                return;
            }

            // Initialize Tenancy for the tenant
            tenancy()->initialize($this->tenant);

            $destinationPath = base_path('Modules/' . $moduleName);
            $configPath = $destinationPath . '/Config/config.php';

            if (!File::exists($configPath)) {
                Log::error("Module installation failed: 'Config/config.php' not found in module {$moduleName}.");
                return;
            }

            // Run Tenant Migrations for this module
            Artisan::call('module:migrate', ['module' => $moduleName, '--force' => true]);

            // Run Tenant Seeders
            Artisan::call('module:seed', ['module' => $moduleName, '--force' => true]);

            // Publish Assets
            // Artisan::call('module:publish', ['module' => $moduleName]);

            // Clear optimization cache
            Artisan::call('optimize:clear');

            Log::info("Module '{$moduleName}' installed successfully for tenant {$this->tenant->id}.");

        } catch (\Exception $e) {
            Log::error("Failed to install module {$this->moduleName} for tenant {$this->tenant->id}: " . $e->getMessage());
            throw $e;
        } finally {
            // End Tenancy to clean up scope
            tenancy()->end();
        }
    }
}
