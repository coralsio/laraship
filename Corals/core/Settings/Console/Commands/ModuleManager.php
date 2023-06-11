<?php namespace Corals\Settings\Console\Commands;

use Corals\Settings\Facades\Modules;
use Corals\Settings\Models\Module;
use Illuminate\Console\Command;

class ModuleManager extends Command
{
    /*
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'corals:modules {--action= : available options (update,enable,disable,install,uninstall,download) } {--type= : available options (core|module|payment|theme)} {--module_name= : theme or plugin name, pass all for all} {--skip_download= : just execute update patches} {--force= : force execute updates even if update server returns no updates} {--module_license= : module license, this is applicable on download action} {--ignore_backup_message= : bypass backup warning message }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corals Module Manager';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @var string $action
     */
    private $action;

    /**
     * @var string $name
     */
    private $module_name;

    /**
     * @var string $types
     */
    private $type;


    /**
     * @var string $types
     */
    private $skip_download;

    private $force;

    private $module_license;

    private $ignore_backup_message;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Modules::clearModulesSettingsCache();
        Modules::getModulesSettings();

        $this->action = $this->option('action');
        $this->type = $this->option('type');
        $this->module_name = $this->option('module_name');
        $this->skip_download = $this->option('skip_download');
        $this->force = $this->option('force');
        $this->module_license = $this->option('module_license') ?? '';
        $this->ignore_backup_message = $this->option('ignore_backup_message');
        if (!$this->action || !in_array($this->action,
                ['download', 'update', 'enable', 'disable', 'install', 'uninstall'])) {
            $this->error("Please specify action: update|enable|disable|install|uninstall");
            return;
        }

        if (!$this->type || !in_array($this->type, ['core', 'module', 'payment', 'theme'])) {
            $this->error("Please specify type: core|module|payment|theme");
            return;
        }

        if (!$this->module_name) {
            $this->error("Please specify object name using --module_name= , use 'all' for all ");
            return;
        }

        $this->{$this->action}();
    }

    public function enable()
    {
        $module = Module::where('code', $this->module_name)->first();
        if (!$module) {
            $this->error("Module $this->module_name not found ");
            return false;
        }
        if (!$module->installed) {
            $this->error("Module $this->module_name is not installed you need to install it by using install action ");
            return false;
        }
        if ($module->enabled) {
            $this->error("Module $this->module_name is already enabled ");
            return false;
        }
        $module->enabled = 1;
        $module->save();
        $this->info("Module $this->module_name has been enabled successfully ");
    }

    public function disable()
    {
        try {
            if (!$this->ignore_backup_message) {
                if (!$this->confirm("Are you sure you want to disable module : $this->module_name ")) {
                    return false;
                }
            }
            $module = Module::where('code', $this->module_name)->first();
            if (!$module) {
                $this->error("Module $this->module_name not found ");
                return false;
            }
            if (!$module->enabled) {
                $this->error("Module $this->module_name is already disabled ");
                return false;
            }

            if ($module->type == "core") {
                $this->error("Module $this->module_name is core module, cannot be disabled ");
                return false;
            }

            Modules::checkModuleRequired($module);

            $module->enabled = 0;
            $module->save();
            $this->info("Module $this->module_name has been disabled successfully ");
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }

    public function install()
    {
        Modules::getModulesSettings();

        try {
            Modules::install($this->module_name);
            $this->info("Module $this->module_name has been installed successfully ");
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }

    public function download()
    {
        try {
            Modules::downloadNew($this->module_name, $this->module_license);
            $this->info("Module $this->module_name has been downloaded successfully ");
            $this->info("Don't forget to run composer update after module(s) updates");
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }

    public function uninstall()
    {
        if (!$this->ignore_backup_message) {
            if (!$this->confirm("Are you sure you want to uninstall module : $this->module_name ")) {
                return false;
            }
        }
        try {
            Modules::uninstall($this->module_name);
            $this->info("Module $this->module_name has been uninstalled successfully ");
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }

    public function update()
    {
        if (!$this->ignore_backup_message) {
            if (!$this->confirm('Its highly recommended to backup application files and database before performing updates, are you sure you want to proceed ?')) {
                return false;
            }
        }

        if (in_array($this->type, ['core', 'module', 'payment'])) {
            $modules = Modules::getModulesSettings();

            $installed_modules = $modules->map(function ($item) {
                return ['version' => $item->version, 'code' => $item->code, 'license_key' => $item->installed_version];
            });
            $installed_modules = $installed_modules->toArray();

            $remote_updates = $this->checkForUpdates('modules_remote_updates', $installed_modules);


            if (!$remote_updates && !$this->force) {
                return false;
            }

            if ($this->module_name != "all") {
                $this->updateModule($this->module_name, $remote_updates);
            } else {
                foreach ($modules as $module) {
                    if ($module->type == $this->type) {
                        try {
                            $this->updateModule($module->code, $remote_updates);
                        } catch (\Exception $exception) {
                            $this->error($exception->getMessage());
                        }
                    }
                }
            }
            $this->info("Don't forget to run composer update after module(s) updates");
        } elseif ($this->type == "theme") {
            $themes = collect(\Theme::all());
            $installed_themes = [];

            foreach ($themes as $theme) {
                $installed_themes[] = ['version' => $theme->version, 'code' => $theme->name, 'license_key' => null];
            }


            $remote_updates = $this->checkForUpdates('themes_remote_updates', $installed_themes);
            if (!$remote_updates) {
                return false;
            }
            if ($this->module_name != "all") {
                $this->updateTheme($this->module_name, $remote_updates, $themes);
            } else {
                foreach ($themes as $theme) {
                    try {
                        $this->updateTheme($theme->name, $remote_updates, $themes);
                    } catch (\Exception $exception) {
                        $this->error($exception->getMessage());
                    }
                }
            }
        } else {
            $this->error("Invalid module type: { $this->type } supported: core|module|payment|theme ");
            return false;
        }
    }

    private function updateTheme($theme_name, $remote_updates, $themes)
    {
        if (!$remote_updates) {
            $remote_updates = [];
        }
        $installed_theme = $themes->where('name', $theme_name)->first();
        $tempPath = \Theme::theme_packages_path('tmp');
        \Theme::createTempFolder();
        $this->info("$theme_name installed version : " . $installed_theme->version, 'v');

        if (array_key_exists($theme_name, $remote_updates)) {
            $this->info("$theme_name remote version : " . $remote_updates[$theme_name]['version'], 'v');
            if (version_compare($remote_updates[$theme_name]['version'], $installed_theme->version,
                    '>') || $this->force) {
                $this->info("Updating Theme: " . $theme_name);
                Modules::download($theme_name, null, $tempPath);
                $filename = $theme_name . '.zip';
                \Theme::installTheme($filename, true);
            } else {
                $this->info("$theme_name has latest version installed");
            }
        } else {
            $this->info("$theme_name has no update information", 'v');
        }
    }

    private function updateModule($module_code, $remote_updates)
    {
        if (!$remote_updates) {
            $remote_updates = [];
        }

        $module = Modules::getModulesSettings($module_code);
        $this->info("$module->code downloaded version : " . $module->version, 'v');
        if (array_key_exists($module->code, $remote_updates)) {
            $this->info("$module->code remote version : " . $remote_updates[$module->code]['version'], 'v');
            if (version_compare($remote_updates[$module->code]['version'], $module->version, '>') || $this->force) {
                if (!$this->skip_download) {
                    $this->info("Downloading Module: " . $module->name);
                    Modules::downloadRemote($module->code);
                } else {
                    $this->info("$module->code  won't be downloaded, skip_download option provided", 'v');
                }
            } else {
                $this->info("$module->code has latest version downloaded", 'v');
            }
        } else {
            $this->info("$module->code has no update information", 'v');
        }

        if ($module->installed) {
            $this->info("Updating Module: " . $module->name);
            Modules::update($module->code);
        } else {
            $this->info("Module: " . $module->name . " is not installed", 'v');
        }
    }

    private function checkForUpdates($type, $installed)
    {
        $check_updates_result = Modules::checkForUpdates(['check-for-updates' => true], $installed, $type, true);
        $remote_updates = $check_updates_result['remote_updates'];
        $this->info("Update Server Response : \n " . print_r($check_updates_result, true), 'vv');

        if (isset($check_updates_result['message']) && !empty($check_updates_result['message'])) {
            $this->info($check_updates_result['message']);
            if ($check_updates_result['status'] == "error") {
                return false;
            }
        }

        if (!$check_updates_result['has_updates']) {
            $this->info("All modules are up to date");
            if (!$this->force) {
                return false;
            }
        }
        return $remote_updates;
    }


}
