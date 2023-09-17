<?php

namespace Corals\Foundation;

use Corals\Activity\ActivityServiceProvider;
use Corals\Elfinder\ElfinderServiceProvider;
use Corals\Foundation\Classes\Password\PasswordBrokerManager;
use Corals\Foundation\Console\Commands\CoralsInstallation;
use Corals\Foundation\Console\Commands\MakeModule;
use Corals\Foundation\Console\Commands\ModelCacheFlush;
use Corals\Foundation\Console\Commands\TranslateLocalisation;
use Corals\Foundation\Facades\Actions;
use Corals\Foundation\Facades\CoralsForm;
use Corals\Foundation\Facades\Filters;
use Corals\Foundation\Facades\Language;
use Corals\Foundation\Http\Middleware\CoralsMiddleware;
use Corals\Foundation\Http\Middleware\JSONResponse;
use Corals\Foundation\Http\Middleware\SetLocale;
use Corals\Foundation\Models\Language\Translation;
use Corals\Foundation\Providers\Breadcrumb\BreadcrumbsServiceProvider;
use Corals\Foundation\Providers\RouteServiceProvider;
use Corals\Foundation\Search\FulltextServiceProvider;
use Corals\Foundation\Shortcodes\Facades\Shortcode;
use Corals\Foundation\Shortcodes\ShortcodesServiceProvider;
use Corals\Foundation\View\Facades\JavaScriptFacade;
use Corals\Foundation\View\Transformers\Transformer;
use Corals\Foundation\View\ViewBinder\CoralsViewBinder;
use Corals\Media\MediaServiceProvider;
use Corals\Menu\Facades\Menus;
use Corals\Menu\MenuServiceProvider;
use Corals\Settings\Facades\Modules;
use Corals\Settings\Facades\Settings;
use Corals\Settings\SettingsServiceProvider;
use Corals\Theme\Facades\Theme;
use Corals\Theme\ThemeServiceProvider;
use Corals\User\Communication\Facades\CoralsNotification;
use Corals\User\Facades\Roles;
use Corals\User\Facades\TwoFactorAuth;
use Corals\User\UserServiceProvider;
use Hashids\Hashids;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Passwords\PasswordBrokerManager as BasePasswordBrokerManager;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Spatie\Html\Html;
use Yajra\DataTables\DataTableAbstract;

class FoundationServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Load view
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Corals');
        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Corals');
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        if (!$this->app->runningInConsole()) {
            \JavaScript::put([
                'utility_google_address_api_key' => \Settings::get('utility_google_address_api_key'),
                'utility_google_address_country' => \Settings::get('utility_google_address_country')
            ]);
        }

        if (request()->is('*api*')) {
            $this->mobileResetPasswordConfiguration();
        }

        DataTableAbstract::macro('getTransformer', function () {
            return $this->transformer;
        });


        $this->registerMissingHtmlMacro();
    }

    /**
     *
     */
    protected function registerMissingHtmlMacro(): void
    {

        Html::macro('style', function ($url, $attributes = [], $secure = null) {

            $defaults = ['media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet'];

            $attributes = array_merge($defaults, $attributes);

            $attributes['href'] = url()->asset($url, $secure);

            return new HtmlString('<link' . $this->attributes($attributes) . '>');

        });

        Html::macro('script', function ($url, $attributes = [], $secure = null) {

            $attributes['src'] = url()->asset($url, $secure);

            return new HtmlString(
                '<script' . $this->attributes($attributes) . '></script>'
            );
        });

        Html::macro('attributes', function ($attributes) {
            $html = [];

            $attributeElement = function ($key, $value) {
                if (is_numeric($key)) {
                    return $value;
                }

                // Treat boolean attributes as HTML properties
                if (is_bool($value) && $key !== 'value') {
                    return $value ? $key : '';
                }

                if (is_array($value) && $key === 'class') {
                    return 'class="' . implode(' ', $value) . '"';
                }

                if (!is_null($value)) {
                    return $key . '="' . e($value, false) . '"';
                }
            };

            foreach ((array)$attributes as $key => $value) {


                $element = $attributeElement($key, $value);

                if (!is_null($element)) {
                    $html[] = $element;
                }
            }

            return count($html) > 0 ? ' ' . implode(' ', $html) : '';
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $helpers = \File::glob(__DIR__ . '/Helpers/*.php');

        foreach ($helpers as $helper) {
            require_once $helper;
        }

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(FulltextServiceProvider::class);
        $this->app->register(ShortcodesServiceProvider::class);
        $this->app->register(UserServiceProvider::class);
        $this->app->register(MenuServiceProvider::class);
        $this->app->register(SettingsServiceProvider::class);
        $this->app->register(ActivityServiceProvider::class);
        $this->app->register(MediaServiceProvider::class);
        $this->app->register(ElfinderServiceProvider::class);
        $this->app->register(ThemeServiceProvider::class);
        $this->app->register(BreadcrumbsServiceProvider::class);

        $this->app->singleton('JavaScript', function ($app) {
            return new Transformer(
                new CoralsViewBinder($app['events'], config('javascript.bind_js_vars_to_this_view')),
                config('javascript.js_namespace')
            );
        });


        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Arr', Arr::class);
            $loader->alias('Str', Str::class);
            $loader->alias('Language', Language::class);
            $loader->alias('Theme', Theme::class);
            $loader->alias('Roles', Roles::class);
            $loader->alias('CoralsForm', CoralsForm::class);
            $loader->alias('Actions', Actions::class);
            $loader->alias('Filters', Filters::class);
            $loader->alias('Menus', Menus::class);
            $loader->alias('Settings', Settings::class);
            $loader->alias('Modules', Modules::class);
            $loader->alias('Shortcode', Shortcode::class);
            $loader->alias('TwoFactorAuth', TwoFactorAuth::class);
            $loader->alias('CoralsNotification', CoralsNotification::class);
            $loader->alias('JavaScript', JavaScriptFacade::class);
        });

        $this->app['router']->pushMiddlewareToGroup('web', CoralsMiddleware::class);
        $this->app['router']->pushMiddlewareToGroup('web', SetLocale::class);
        $this->app['router']->pushMiddlewareToGroup('api', JSONResponse::class);


        $this->app['router']->middlewarePriority = [
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            JSONResponse::class,
            \Illuminate\Auth\Middleware\Authenticate::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Auth\Middleware\Authorize::class,
        ];

        Actions::do_action('post_coral_registration');

        // Bind 'hashids' shared component to the IoC container
        $this->app->singleton('hashids', function ($app) {
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

            $salt = hash('sha256', 'Corals');

            return new Hashids($salt, 10, $alphabet);
        });

        $this->registerConfig();
        $this->registerCommand();
    }

    /**
     *
     */
    protected function mobileResetPasswordConfiguration(): void
    {
        $this->resetPasswordEmailCallback();
        $this->extendPasswordBroker();
    }

    /**
     *
     */
    protected function resetPasswordEmailCallback()
    {
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new MailMessage)
                ->subject(Lang::get('Reset Password Notification'))
                ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
                ->line(new HtmlString("<h1>$token</h1>"))
                ->line(Lang::get('This password token will expire in :count minutes.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
                ->line(Lang::get('If you did not request a password reset, no further action is required.'));
        });
    }

    /**
     *
     */
    protected function extendPasswordBroker(): void
    {
        $this->app->extend('auth.password', function (BasePasswordBrokerManager $passwordBroker, $app) {
            return new PasswordBrokerManager($app);
        });
    }

    protected function registerMorphMaps()
    {
        Relation::morphMap([
            'Translation' => Translation::class,
        ]);
    }

    protected function registerConfig()
    {
        $configFiles = \File::glob(__DIR__ . '/config/*.php');

        foreach ($configFiles as $config) {
            $key = basename($config, '.php');
            $this->mergeConfigFrom(__DIR__ . "/config/$key.php", $key);
        }
    }

    protected function registerCommand()
    {
        $this->commands(CoralsInstallation::class);
        $this->commands(MakeModule::class);
        $this->commands(ModelCacheFlush::class);
        $this->commands(TranslateLocalisation::class);
    }

    public function provides()
    {
        return ['hashids', 'JavaScript'];
    }
}
