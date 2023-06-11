<?php namespace Corals\Theme;

use Corals\Settings\Facades\Settings;
use Corals\Theme\Facades\Theme;
use Corals\Theme\Providers\ThemeRouteServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ThemeServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/themes.php', 'themes');
        /*--------------------------------------------------------------------------
        | Bind in IOC
        |--------------------------------------------------------------------------*/

        $this->app->singleton('corals.themes', function () {
            return new Themes();
        });

        /*--------------------------------------------------------------------------
        | Replace FileViewFinder
        |--------------------------------------------------------------------------*/

        $this->app->singleton('view.finder', function ($app) {
            return new ThemeViewFinder(
                $app['files'],
                $app['config']['view.paths'],
                null
            );
        });

        $this->app->register(ThemeRouteServiceProvider::class);
    }

    public function provides()
    {
        return [
            ThemeViewFinder::class,
            Themes::class
        ];
    }

    public function boot()
    {
        /*--------------------------------------------------------------------------
        | Register helpers.php functions
        |--------------------------------------------------------------------------*/

        require_once 'Helpers/helpers.php';


        /*--------------------------------------------------------------------------
        | Initialize Themes
        |--------------------------------------------------------------------------*/

        $themes = $this->app->make('corals.themes');
        $themes->scanThemes();

        /*--------------------------------------------------------------------------
        | Activate default theme
        |--------------------------------------------------------------------------*/
        if (!$themes->current() && \Config::get('themes.default')) {
            $themes->set(\Config::get('themes.default'));
        }

        /*--------------------------------------------------------------------------
        | Pulish configuration file
        |--------------------------------------------------------------------------*/

        $this->publishes([
            __DIR__ . '/Config/themes.php' => config_path('themes.php'),
        ], 'laravel-theme');

        /*--------------------------------------------------------------------------
        | Register Commands
        |--------------------------------------------------------------------------*/

        $this->commands([
            \Corals\Theme\Commands\listThemes::class,
            \Corals\Theme\Commands\createTheme::class,
            \Corals\Theme\Commands\removeTheme::class,
            \Corals\Theme\Commands\createPackage::class,
            \Corals\Theme\Commands\installPackage::class,
            \Corals\Theme\Commands\refreshCache::class,
        ]);

        if (!app()->runningInConsole()) {
            /*--------------------------------------------------------------------------
            | Register custom Blade Directives
            |--------------------------------------------------------------------------*/

            $this->registerBladeDirectives();

            // Load view
            $this->loadViewsFrom(__DIR__ . '/resources/views', 'Theme');

            // Load lang
            $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Theme');

            rescue(function () {
                $adminTheme = \Theme::find(\Settings::get('active_admin_theme', config('themes.corals_admin')));

                $this->loadThemeTranslations($adminTheme);

                $frontEndTheme = \Theme::find(\Settings::get('active_frontend_theme',
                    config('themes.corals_frontend')));
                $this->loadThemeTranslations($frontEndTheme);
            });

            $this->mapThemeRoutes();
        }
    }

    protected function loadThemeTranslations($theme)
    {
        $path = $theme->getViewPaths()[0] ?? null;

        if ($path) {
            $path .= '/lang';

            $this->loadTranslationsFrom($path, $theme->name);
        }

        if (!is_null($theme->parent)) {
            $this->loadThemeTranslations($theme->parent);
        }
    }

    protected function registerBladeDirectives()
    {
        /*--------------------------------------------------------------------------
        | Extend Blade to support Orcherstra\Asset (Asset Managment)
        |
        | Syntax:
        |
        |   @css (filename, alias, depends-on-alias)
        |   @js  (filename, alias, depends-on-alias)
        |--------------------------------------------------------------------------*/

        Blade::extend(function ($value) {
            return preg_replace_callback('/\@js\s*\(\s*([^),]*)(?:,\s*([^),]*))?(?:,\s*([^),]*))?\)/',
                function ($match) {
                    $p1 = trim($match[1], " \t\n\r\0\x0B\"'");
                    $p2 = trim(empty($match[2]) ? $p1 : $match[2], " \t\n\r\0\x0B\"'");
                    $p3 = trim(empty($match[3]) ? '' : $match[3], " \t\n\r\0\x0B\"'");

                    if (empty($p3)) {
                        return "<?php Asset::script('$p2', \Theme::url('$p1'));?>";
                    } else {
                        return "<?php Asset::script('$p2', \Theme::url('$p1'), '$p3');?>";
                    }
                }, $value);
        });

        \Blade::extend(function ($value) {
            return preg_replace_callback('/\@jsIn\s*\(\s*([^),]*)(?:,\s*([^),]*))?(?:,\s*([^),]*))?(?:,\s*([^),]*))?\)/',
                function ($match) {
                    $p1 = trim($match[1], " \t\n\r\0\x0B\"'");
                    $p2 = trim($match[2], " \t\n\r\0\x0B\"'");
                    $p3 = trim(empty($match[3]) ? $p2 : $match[3], " \t\n\r\0\x0B\"'");
                    $p4 = trim(empty($match[4]) ? '' : $match[4], " \t\n\r\0\x0B\"'");

                    if (empty($p4)) {
                        return "<?php Asset::container('$p1')->script('$p3', \\Theme::url('$p2'));?>";
                    } else {
                        return "<?php Asset::container('$p1')->script('$p3', \\Theme::url('$p2'), '$p4');?>";
                    }
                }, $value);
        });


        Blade::extend(function ($value) {
            return preg_replace_callback('/\@css\s*\(\s*([^),]*)(?:,\s*([^),]*))?(?:,\s*([^),]*))?\)/',
                function ($match) {
                    $p1 = trim($match[1], " \t\n\r\0\x0B\"'");
                    $p2 = trim(empty($match[2]) ? $p1 : $match[2], " \t\n\r\0\x0B\"'");
                    $p3 = trim(empty($match[3]) ? '' : $match[3], " \t\n\r\0\x0B\"'");

                    if (empty($p3)) {
                        return "<?php Asset::style('$p2', \Theme::url('$p1'));?>";
                    } else {
                        return "<?php Asset::style('$p2', \Theme::url('$p1'), '$p3');?>";
                    }
                }, $value);
        });
    }


    protected function mapThemeRoutes()
    {
        $themesPath = Theme::themes_path();
        $theme = Theme::find(Settings::get('active_frontend_theme', config('themes.corals_frontend')));
        $this->loadThemeRoutes($theme, $themesPath);
    }

    protected function loadThemeRoutes($theme, $themesPath)
    {
        if ($theme->getParent()) {
            $this->loadThemeRoutes($theme->getParent(), $themesPath);
        }

        $publicThemePath = $themesPath . '/' . $theme->viewsPath;

        $this->autoloadThemeClasses($publicThemePath);

        $configFilesPath = $publicThemePath . DIRECTORY_SEPARATOR . 'config';

        if (file_exists($configFilesPath)) {
            $configFiles = File::allFiles($configFilesPath);

            foreach ($configFiles as $file) {
                $config = $file->getBasename('.php');

                $this->mergeConfigFrom($configFilesPath . '/' . $file->getBasename(), $config);
            }
        }

        $webPath = $publicThemePath . '/routes/web.php';

        if (file_exists($webPath)) {
            Route::middleware('web')
                ->namespace($theme->routeNamespace)
                ->group($webPath);
        }
    }

    /**
     * @param $path
     */
    protected function autoloadThemeClasses($path): void
    {
        if (file_exists($path)) {
            $fileSystem = new Filesystem();
            foreach ($fileSystem->allFiles($path) as $file) {
                if (Str::contains($file->getRelativePath(), ['Http', 'Classes'])) {
                    require_once $file->getRealPath();
                }
            }
            if (file_exists($path . DIRECTORY_SEPARATOR . "functions.php")) {
                require_once($path . DIRECTORY_SEPARATOR . "functions.php");
            }
        }
    }

}
