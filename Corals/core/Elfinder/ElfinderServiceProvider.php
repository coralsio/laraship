<?php namespace Corals\Elfinder;

use Corals\Elfinder\Providers\ElfinderRouteServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ElfinderServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/config/elfinder.php';

        $this->mergeConfigFrom($configPath, 'elfinder');

        $this->app->register(ElfinderRouteServiceProvider::class);
    }

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        $viewPath = __DIR__ . '/resources/views';

        $this->loadViewsFrom($viewPath, 'elfinder');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'elfinder');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
    }

}
