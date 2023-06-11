<?php

namespace Corals\Foundation\Shortcodes;

use Illuminate\Support\ServiceProvider;

class ShortcodesServiceProvider extends ServiceProvider
{

    protected $defer = false;

    /**
     * Boot the application events.
     */
    public function boot()
    {
        \Blade::directive('widget', function ($expression) {
            $segments = explode(',', preg_replace("/[\(\)\\\]/", '', $expression));

            if (!array_key_exists(1, $segments)) {
                return '<?php echo (new \Corals\Foundation\Shortcodes\Service\Widget)->get(' . $segments[0] . '); ?>';
            }

            return '<?php echo (new \Corals\Foundation\Shortcodes\Service\Widget)->get(' . $segments[0] . ',' . $segments[1] . '); ?>';
        });
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerShortcode();
    }

    /**
     * Register the Shortcode
     */
    public function registerShortcode()
    {
        $this->app->singleton('shortcode', function ($app) {
            return new Shortcode();
        });
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'shortcode',
        );
    }

}
