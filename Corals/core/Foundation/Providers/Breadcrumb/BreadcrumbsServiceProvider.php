<?php

namespace Corals\Foundation\Providers\Breadcrumb;

// Not available until Laravel 5.8
//use Illuminate\Contracts\Support\DeferrableProvider;
use Corals\Foundation\Classes\Breadcrumb\BreadcrumbsGenerator;
use Corals\Foundation\Classes\Breadcrumb\BreadcrumbsManager;
use Corals\Foundation\Facades\Breadcrumb\Breadcrumbs;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

/**
 * The Laravel service provider, which registers, configures and bootstraps the package.
 */
class BreadcrumbsServiceProvider extends ServiceProvider //implements DeferrableProvider
{
    public function isDeferred()
    {
        // Remove this and uncomment DeferrableProvider after dropping support
        // for Laravel 5.7 and below
        return true;
    }

    /**
     * Get the services provided for deferred loading.
     *
     * @return array
     */
    public function provides(): array
    {
        return [BreadcrumbsManager::class];
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        // Load the default config values
        $configFile = __DIR__ . '/../../config/breadcrumbs.php';
        $this->mergeConfigFrom($configFile, 'breadcrumbs');

        // Publish the config/breadcrumbs.php file
        $this->publishes([$configFile => config_path('breadcrumbs.php')], 'breadcrumbs-config');

        // Register Manager class singleton with the app container
        $this->app->singleton(BreadcrumbsManager::class, config('breadcrumbs.manager-class'));

        // Register Generator class so it can be overridden
        $this->app->bind(BreadcrumbsGenerator::class, config('breadcrumbs.generator-class'));

        // Register 'breadcrumbs::' view namespace
        $this->loadViewsFrom(__DIR__ . '/../../resources/views/Breadcrumb', 'breadcrumbs');

        $this->registerFacades();
    }

    /**
     *
     */
    protected function registerFacades(): void
    {
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Breadcrumbs', Breadcrumbs::class);

        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        // Load the routes/breadcrumbs.php file
        $this->registerBreadcrumbs();
    }

    /**
     * Load the routes/breadcrumbs.php file (if it exists) which registers available breadcrumbs.
     *
     * This method can be overridden in a child class. It is called by the boot() method, which Laravel calls
     * automatically when bootstrapping the application.
     *
     * @return void
     */
    public function registerBreadcrumbs(): void
    {
        // Load the routes/breadcrumbs.php file, or other configured file(s)
        $files = config('breadcrumbs . files');

        if (!$files) {
            return;
        }

        // If it is set to the default value and that file doesn't exist, skip loading it rather than causing an error
        if ($files === base_path('routes/breadcrumbs.php') && !is_file($files)) {
            return;
        }

        // Support both Breadcrumbs:: and $breadcrumbs-> syntax by making $breadcrumbs variable available
        /** @noinspection PhpUnusedLocalVariableInspection */
        $breadcrumbs = $this->app->make(BreadcrumbsManager::class);

        // Support both a single string filename and an array of filenames (e.g. returned by glob())
        foreach ((array)$files as $file) {
            require $file;
        }
    }
}
