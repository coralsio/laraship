<?php

namespace App\Console\Commands;

use Corals\Settings\Facades\Modules;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class LarashipDeployment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:ls {--force} {--dummy}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'deploy ls updateOrInstall modules';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('run clear caches command');
        Artisan::call('c:c');
        $this->line(Artisan::output());
        Artisan::call('migrate');
        $this->line(Artisan::output());

        if (!Schema::hasTable('users') || $this->option('force')) {
            $this->line('Start fresh installation');
            $freshCommands = ['migrate:fresh', 'db:seed'];
            foreach ($freshCommands as $command) {
                $this->line($command);
                Artisan::call($command);
                $this->line(Artisan::output());
            }
        }

        $availableModules = Modules::getModulesSettings();

        $this->line('Check and update core modules');

        $availableModules->where('type', 'core')->each(function ($module) {
            Modules::update($module->code);
        });

        $sortedModules = [
            'corals-activity',
            'corals-file-manager',
            'corals-foundation',
            'corals-media',
            'corals-menu',
            'corals-settings',
            'corals-theme',
            'corals-user',
            'corals-utility',
        ];

        $this->line('Update or install required modules');

        foreach ($sortedModules as $moduleCode) {
            if ($availableModules->where('code', $moduleCode)->where('installed')->isEmpty()) {
                $this->line(sprintf('%s:: %s', $moduleCode, 'install'));
                try {
                    Modules::install($moduleCode);
                } catch (\Exception $exception) {
                    $this->line(sprintf(
                        '%s:: %s because of: %s',
                        $moduleCode,
                        'retry install',
                        $exception->getMessage()
                    ));
                    Modules::uninstall($moduleCode);
                    //do another try in case of failure
                    Modules::install($moduleCode);
                }
            } else {
                $this->line(sprintf('%s:: %s', $moduleCode, 'update'));
                Modules::update($moduleCode);
            }
        }

        $this->line('run clear caches command');
        Artisan::call('c:c');

        return 0;
    }
}
