<?php

namespace Corals\Foundation\Console\Commands;

use Corals\Foundation\Classes\Installation\ConfigureDatabase;
use Corals\Foundation\Classes\Installation\ConfigureLicense;
use Corals\Settings\Facades\Modules;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CoralsInstallation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'corals:install';

    protected $selectedPlatform = null;
    protected $selectedPayment = null;
    protected $skipOption = 'Skip!!';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corals installation options';

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
     * @throws \Exception
     */
    public function handle()
    {
        $installOptions = ['Fresh Installation', 'Clear All Cache'];

        $choice = $this->choice('Select your install option:', $installOptions);

        $methodName = Str::camel($choice);

        if (method_exists($this, $methodName)) {
            $this->{$methodName}();
        } else {
            throw new \Exception('Invalid Selection: ' . $choice);
        }
    }

    /**
     * @return false|void
     * @throws BindingResolutionException
     */
    public function freshInstallation()
    {
        $checkFailed = $this->preInstallationChecker();

        if ($checkFailed) {
            $this->warn('Please check records was not marked as success!!');
            return false;
        }

        $this->clearAllCache(false);

        $this->configureLicense();

        $this->configureDatabase();

        $result = $this->confirm('You are going to do fresh installation, are you sure?');

        if (!$result) {
            return;
        }

        $this->info('Starting Fresh Installation...');

        $commands = [
            ['command' => 'migrate:fresh', 'args' => ['--force' => true, '--seed' => true]],
            'passport:install',
            'key:generate'
        ];

        $this->executeCommands($commands);

        $this->scanAndInstallModules();

        $this->scanAndInstallTheme();

        $this->line("Thank you for trusting Laraship, Enjoy!!");
    }

    protected function scanAndInstallTheme()
    {
        if ($this->selectedPlatform == $this->skipOption) {
            return;
        }

        $result = $this->confirm('Do you want to install a theme now?', true);

        if (!$result) {
            return;
        }

        $themes = collect(\Theme::all());

        $frontend_themes = $themes->where('type', 'frontend')->all();

        $available_themes = collect([]);

        foreach ($frontend_themes as $theme) {
            if (isset($theme->settings['platform'])) {
                $platform = $theme->settings['platform'];
                if (is_array($platform)) {
                    $platform = join('|', $platform);
                }

                if (!Str::contains($platform, $this->selectedPlatform)) {
                    continue;
                }
            }
            $available_themes->push(['code' => $theme->name, 'caption' => $theme->caption]);
        }

        $themesList = $available_themes->pluck('caption')->toArray();

        if (count($themesList) > 0) {
            $selectedTheme = $this->choice('Select a theme to install:', $themesList);
        } else {
            $selectedTheme = null;
        }

        $selectedTheme = $available_themes->where('caption', $selectedTheme)->first();

        \Settings::set("active_frontend_theme", $selectedTheme['code']);

        $this->line("{$selectedTheme['caption']} Has been activated successfully");

        $result = $this->confirm('Do you want to import theme demo data?', true);

        if ($result) {
            // Get the theme
            $theme = \Theme::find($selectedTheme['code']);

            $theme->importDemo();

            $this->line("{$selectedTheme['caption']} demo data has been imported successfully");
        }
    }

    protected function scanAndInstallModules()
    {
        $modules = \Modules::getModulesSettings();

        $modules = $modules->where('type', '<>', 'core')
            ->whereNotIn('code', ['corals-demo']);

        $modulesToInstall = collect([]);

//        logger('$modules');
//        logger($modules->pluck('code')->toArray());

        $modules->map(function ($module) use (&$modulesToInstall, $modules) {
            foreach ($module->require as $code => $version) {
                $requireModule = $modules->where('code', $code)->first();
                if ($requireModule) {
                    $this->pushModuleToInstall($modulesToInstall, $requireModule);
                }
            }

            $this->pushModuleToInstall($modulesToInstall, $module);
        });

//        logger('$modulesToInstall');
//        logger($modulesToInstall->pluck('code')->toArray());

        $platforms = [];

        $modulesToInstall->map(function ($module) use (&$platforms) {
            if (isset($module['module']->platform)) {
                $platform = $module['module']->platform;
                if (is_array($platform)) {
                    $platforms = array_merge($platforms, $platform);
                } else {
                    array_push($platforms, $platform);
                }
            }
        });

        $platforms = array_values(array_unique(array_filter($platforms)));

        $platforms[] = $this->skipOption;

        $selectedPlatform = $this->choice('Select platform to install:', $platforms);

        $this->selectedPlatform = $selectedPlatform;

        if ($selectedPlatform == $this->skipOption) {
            return;
        }

        $modulesToInstall = $modulesToInstall->filter(function ($module) {
            $module = $module['module'];

            $supported_platforms = [];

            if (isset($module->supported_platforms) && !empty($module->supported_platforms)) {
                $supported_platforms = $module->supported_platforms;
            }

            $platform = null;

            if (isset($module->platform) && !empty($module->platform)) {
                $platform = $module->platform;
                if ($platform == $this->selectedPlatform) {
                    return true;
                }
            }

            if (
                (empty($supported_platforms) && !empty($module->platform) && $platform != $this->selectedPlatform)
                ||
                (!empty($supported_platforms) && !in_array($this->selectedPlatform, $supported_platforms))
            ) {
                return false;
            }

            return true;
        });

        $modulesToInstallTypeModule = $modulesToInstall->filter(function ($module) {
            $module = $module['module'];

            if ($module->type == 'module') {
                return true;
            }

            return false;
        });

        $this->line("Start {$this->selectedPlatform} platform installation... \n");

//        logger('$modulesToInstallTypeModule');
//        logger($modulesToInstallTypeModule->pluck('code')->toArray());

        $this->installModules($modulesToInstallTypeModule);

        $modulesToInstallTypePayment = $modulesToInstall->filter(function ($module) {
            $module = $module['module'];

            if ($module->type == 'payment') {
                return true;
            }

            return false;
        });

        if ($modulesToInstallTypePayment->isNotEmpty()) {
            $paymentsOptions = $modulesToInstallTypePayment->pluck('name')->toArray();

            $paymentsOptions[] = $this->skipOption;

            $selectedPayment = $this->choice('Select a payment gateway to install:', $paymentsOptions);

            $this->selectedPayment = $selectedPayment;

            if ($selectedPayment != $this->skipOption) {
                $modulesToInstallTypePayment = $modulesToInstallTypePayment->whereIn('name',
                    Arr::wrap($selectedPayment));
                $this->line("Start {$this->selectedPayment} installation... \n");
                $this->installModules($modulesToInstallTypePayment);
            }
        }

        $this->info("\n{$this->selectedPlatform} Installation Ready... ✔ 100% \n");
    }

    protected function installModules($modules)
    {
        $bar = $this->output->createProgressBar($modules->count());

        $bar->start();

        $requireModules = collect([]);

        $modules->sortByDesc('score')->map(function ($module) use (&$bar, &$requireModules, $modules) {
            $moduleObject = $module['module'];

            $this->line("\n[$moduleObject->code] installing...");

            $dependencyMissing = false;

            foreach ($moduleObject->require as $code => $version) {
                if (!Modules::isModuleActive($code)) {
                    $requireModule = $modules->where('code', $code)->first();

                    if ($requireModule) {
                        $dependencyMissing = true;
                        $requireModules->push($requireModule);
                    }
                }
            }

            if ($dependencyMissing) {
                $requireModules->push($module);
                $this->line("[$moduleObject->code] dependency required...");
            } else {
                if (!Modules::isModuleActive($moduleObject->code)) {
                    Modules::install($moduleObject->code);

                    $this->line("[$moduleObject->code] completed...");
                }
            }

            $bar->advance();
        });

        if ($requireModules->isNotEmpty()) {
            $this->line("Installing Dependencies");
            $this->installModules($requireModules);
        }
    }

    protected function pushModuleToInstall(&$modulesToInstall, $module)
    {
        $code = $module->code;

        $requireModuleToInstall = $modulesToInstall->where('code', $code)->first();

        if ($requireModuleToInstall) {
            $modulesToInstall = $modulesToInstall->map(function ($module) use ($code) {
                if ($code == $module['code']) {
                    $module['score'] *= 10;
                }
                return $module;
            });
        } else {
            $modulesToInstall->push([
                'name' => $module->name,
                'code' => $module->code,
                'module' => $module,
                'score' => 1
            ]);
        }
    }

    /**
     * @throws BindingResolutionException
     */
    protected function configureDatabase()
    {
        $configureDB = app()->make(ConfigureDatabase::class);

        $configureDB->fire($this);

        DB::reconnect();

        $this->executeCommands(['config:cache', 'config:clear']);
    }

    protected function configureLicense()
    {
        $configureLicense = app()->make(ConfigureLicense::class);

        $configureLicense->fire($this);
    }

    /**
     * @return bool
     */
    public function preInstallationChecker()
    {
        $this->info('Checking Platform Requirements...');

        $checkResult = shell_exec('composer check-platform-reqs');

        $checkResult = array_filter(preg_split('/\r\n|\r|\n/', $checkResult));

        $checkResult = array_map(function ($item) {
            $itemArr = array_values(array_filter(explode(' ', trim($item))));

            return [
                'extension' => $itemArr[0],
                'version' => $itemArr[1],
                'status' => $itemArr[2],
            ];
        }, $checkResult);

        $customExt = [
            'or' => [
                ['BCMath', 'GMP'], //[],[]
            ],
            'and' => [] // '',''
        ];

        foreach ($customExt as $type => $groups) {
            switch ($type) {
                case 'and':
                    foreach ($groups as $ext) {
                        $extArr = [
                            'extension' => $ext,
                            'version' => '-',
                            'status' => 'N/A',
                        ];

                        if (extension_loaded($ext)) {
                            $extArr['status'] = 'success';
                        }

                        $checkResult[] = $extArr;
                    }
                    break;
                case 'or':
                    foreach ($groups as $group) {
                        $extArr = [
                            'extension' => join(' or ', $group),
                            'version' => '-',
                            'status' => 'N/A',
                        ];
                        foreach ($group as $ext) {
                            if (extension_loaded($ext)) {
                                $extArr['status'] = 'success';
                                break;
                            }
                        }

                        $checkResult[] = $extArr;
                    }
                    break;
            }
        }

        $headers = ['Extension', 'Version', 'Status'];

        $this->table($headers, $checkResult);

        $failedExtCount = collect($checkResult)->where('status', '<>', 'success')->count();

        return $failedExtCount > 0;
    }

    public function clearAllCache($confirm = true)
    {
        if ($confirm) {
            $result = $this->confirm('Proceed in cache clear?', true);

            if (!$result) {
                return;
            }
        }

        $cacheCommands = [
            'cache:clear',
            'config:clear',
            'view:clear',
            'theme:refresh-cache',
            'debugbar:clear',
            'queue:restart'
        ];

        $this->executeCommands($cacheCommands);

        $this->info('Cache cleared.');
    }

    protected function executeCommands($commands)
    {
        $bar = $this->output->createProgressBar(count($commands));

        $bar->start();

        foreach ($commands as $command) {
            $args = [];

            if (is_array($command)) {
                $args = $command['args'];
                $command = $command['command'];
            }

            $this->line("\n$command");

            \Artisan::call($command, $args);

            $this->line(\Artisan::output());

            $bar->advance();
        }

        $this->line("\n");
    }
}
