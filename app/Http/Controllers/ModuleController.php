<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;
use Nwidart\Modules\Facades\Module as NwidartModule;

class ModuleController extends Controller
{
    public function index(): View
    {
        $modules = Module::orderBy('name')->paginate(15);

        return view('module.index', compact('modules'));
    }

    public function create(): View
    {
        return view('module.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'module_file' => 'required|file|mimes:zip|max:51200', // Max 50MB
        ]);

        $tempExtractPath = '';
        $zipPath = $request->file('module_file')->getRealPath();
        $zip = new ZipArchive;

        try {
            if ($zip->open($zipPath) !== TRUE) {
                throw new \Exception('Could not open the zip file.');
            }

            // --- SECURITY CHECKS BEFORE EXTRACTION ---
            $foundModuleJson = false;
            $moduleRootInZip = '';

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filePath = $zip->getNameIndex($i);

                // SECURITY CHECK 1: Prevent Path Traversal (Zip Slip)
                if (Str::startsWith($filePath, '..') || Str::contains($filePath, '/../')) {
                    throw new \Exception("The zip file contains a malicious path traversal attempt ('{$filePath}').");
                }

                // SECURITY CHECK 2: Disallow dangerous file types
                $disallowedExtensions = ['phtml', 'sh', 'exe', 'bat', 'cgi'];
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                if (in_array(strtolower($extension), $disallowedExtensions) && basename($filePath) !== 'module.json') {
                    throw new \Exception("Upload blocked: Disallowed file type [{$extension}]");
                }

                if (basename($filePath) === 'module.json') {
                    $foundModuleJson = true;
                    $moduleRootInZip = pathinfo($filePath, PATHINFO_DIRNAME);
                    if ($moduleRootInZip === '.') {
                        $moduleRootInZip = '';
                    } elseif (!empty($moduleRootInZip)) {
                        $moduleRootInZip .= '/';
                    }
                }
            }

            if (!$foundModuleJson) {
                throw new \Exception('The zip file is not a valid module (missing module.json).');
            }
            $zip->close();

            // --- SAFE EXTRACTION ---
            if ($zip->open($zipPath) !== TRUE) {
                throw new \Exception('Could not re-open zip file for extraction.');
            }

            $tempExtractPath = storage_path('app/temp_module_extract_' . time());
            File::makeDirectory($tempExtractPath);

            $filesToExtract = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filePath = $zip->getNameIndex($i);
                if (Str::startsWith($filePath, $moduleRootInZip)) {
                    $filesToExtract[] = $filePath;
                }
            }
            $zip->extractTo($tempExtractPath, $filesToExtract);
            $zip->close();

            $extractedModulePath = $tempExtractPath . '/' . $moduleRootInZip;
            $moduleJsonPath = $extractedModulePath . 'module.json';

            $moduleInfo = json_decode(File::get($moduleJsonPath));
            if (!$moduleInfo || !isset($moduleInfo->name) || empty($moduleInfo->name)) {
                throw new \Exception('module.json is invalid or missing a "name" property.');
            }

            // SECURITY CHECK 3: Stricter module name sanitization
            $moduleName = preg_replace('/[^A-Za-z0-9\-_]/', '', $moduleInfo->name);
            if (empty($moduleName)) {
                throw new \Exception('The module name in module.json is invalid.');
            }

            $destinationPath = base_path('Modules/' . $moduleName);

            if (File::isDirectory($destinationPath)) {
                throw new \Exception("A module named '{$moduleName}' already exists.");
            }

            File::moveDirectory($extractedModulePath, $destinationPath);

            // Create database record
            Module::create([
                'name' => $moduleInfo->name ?? $moduleName,
                'version' => $moduleInfo->version ?? '1.0.0',
                'description' => $moduleInfo->description ?? '',
                'is_active' => false, // Disabled by default
                'icon_path' => $moduleInfo->icon ?? null,
                'price' => $moduleInfo->price ?? 0.00,
            ]);

            // Sync with modules_statuses.json - Default to disabled
            $nwidartModule = NwidartModule::find($moduleName);
            if ($nwidartModule) {
                $nwidartModule->disable();
            }

            return redirect()->route('modules.index')
                ->with('success', "Module '{$moduleName}' was successfully uploaded and created.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Upload failed: ' . $e->getMessage())
                ->withInput();
        } finally {
            if (!empty($tempExtractPath) && File::isDirectory($tempExtractPath)) {
                File::deleteDirectory($tempExtractPath);
            }
        }
    }

    public function toggleStatus(Module $module): RedirectResponse
    {
        $module->is_active = !$module->is_active;
        $module->save();

        // Sync with modules_statuses.json
        $nwidartModule = NwidartModule::find($module->name);

        if ($nwidartModule) {
            if ($module->is_active) {
                $nwidartModule->enable();
            } else {
                $nwidartModule->disable();
            }
        }

        $status = $module->is_active ? 'enabled' : 'disabled';

        return redirect()->route('modules.index')
            ->with('success', "Module '{$module->name}' has been {$status} successfully.");
    }
}
