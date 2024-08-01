<?php

namespace Corals\Utility;

use Corals\Foundation\Facades\Actions;
use Corals\Foundation\Providers\BasePackageServiceProvider;
use Corals\User\Communication\Facades\CoralsNotification;
use Corals\Utility\Facades\Utility;
use Corals\Utility\Hooks\UtilityHook;
use Corals\Utility\Notifications\InvitationAcceptedNotification;
use Corals\Utility\Notifications\UserInvitationNotification;
use Corals\Utility\Providers\UtilityAuthServiceProvider;
use Corals\Utility\Providers\UtilityObserverServiceProvider;
use Corals\Utility\Providers\UtilityRouteServiceProvider;
use Illuminate\Foundation\AliasLoader;

class UtilityServiceProvider extends BasePackageServiceProvider
{
    protected $defer = true;

    protected $packageCode = 'corals-utility';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */

    public function bootPackage()
    {
        // Load view
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'Utility');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'Utility');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->registerMorphMaps();
        $this->registerCustomFieldsModels();
        $this->addEvents();
        $this->registerHooks();

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerPackage()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/utility.php', 'utility');

        $this->app->register(UtilityRouteServiceProvider::class);
        $this->app->register(UtilityAuthServiceProvider::class);
        $this->app->register(UtilityObserverServiceProvider::class);

        $this->app->booted(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Utility', Utility::class);
        });

    }

    protected function registerMorphMaps()
    {
    }

    protected function registerCustomFieldsModels()
    {
    }

    public function registerModulesPackages()
    {
    }

    public function addEvents()
    {
        CoralsNotification::addEvent(
            'notifications.invitation.accepted',
            'New Invitation Accepted',
            InvitationAcceptedNotification::class);

        CoralsNotification::addEvent(
            'notifications.user.invitation',
            'User Invitation',
            UserInvitationNotification::class);

    }

    protected function registerHooks()
    {
        Actions::add_action('pre_registration_submit', [UtilityHook::class, 'pre_registration_submit'], 12);
        Actions::add_action('auth_register_form', [UtilityHook::class, 'auth_register_form'], 1);
        Actions::add_action('user_registered', [UtilityHook::class, 'user_registered'], 10);
        Actions::add_action('invitation_accepted', [UtilityHook::class, 'invitation_accepted'], 5);
    }

}
