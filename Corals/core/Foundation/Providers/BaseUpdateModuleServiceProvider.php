<?php

namespace Corals\Foundation\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class BaseUpdateModuleServiceProvider extends ServiceProvider
{
    protected $module_code = '';
    protected $batches_path = '';
    protected $module_public_path = '';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->booted(function () {
            $this->coralsBooted();
            $this->updatePublicAssets();
            \Cache::flush();

        });
    }

    /**
     * @throws \Exception
     */
    protected function coralsBooted()
    {
        $batches = [];

        $files = \File::glob($this->batches_path);

        foreach ($files as $batch) {
            $batches[basename($batch, '.php')] = $batch;
        }

        \Modules::executeModuleBatches($this->module_code, $batches);
    }

    protected function updatePublicAssets()
    {
        $filesystem = new Filesystem();

        $modulePublicPath = $this->module_public_path;

        if (!empty($modulePublicPath) && $filesystem->exists($modulePublicPath)) {
            $filesystem->copyDirectory($modulePublicPath, public_path('/'));
        }
    }
}
