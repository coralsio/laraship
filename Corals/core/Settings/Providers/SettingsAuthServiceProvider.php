<?php

namespace Corals\Settings\Providers;

use Corals\Settings\Models\CustomFieldSetting;
use Corals\Settings\Models\Module;
use Corals\Settings\Models\Setting;
use Corals\Settings\Policies\CustomFieldSettingPolicy;
use Corals\Settings\Policies\ModulePolicy;
use Corals\Settings\Policies\SettingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class SettingsAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Setting::class => SettingPolicy::class,
        Module::class => ModulePolicy::class,
        CustomFieldSetting::class => CustomFieldSettingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}