<?php

namespace Corals\Modules;

use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        try {
            $module = null;

            $modules = app('db')->select("SELECT provider,id,code FROM modules where enabled=1 and provider is not null and type <> 'core' order by load_order");

            foreach ($modules as $module) {
                $provider = $module->provider;
                if ($provider && class_exists($provider)) {
                    $this->app->register($provider);
                }
            }
        } catch (\Exception $exception) {
            if (isset($module)) {
                app('db')->update('UPDATE modules set enabled=0 , notes= ? where id = ?', [$exception->getMessage(), $module->id]);
                flash('There was an error when loading module: ' . $module->code . "; module has been disabled")->warning();
            }

        }
    }
}
