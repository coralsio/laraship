<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'c:c';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All Cash Clear';

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cacheCommands = [
            'cache:clear',
            'config:clear',
            'route:clear',
            'view:clear',
            'debugbar:clear',
            'queue:restart',
            'route:cache',
            'config:cache'
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

            Artisan::call($command, $args);

            $this->line(Artisan::output());

            $bar->advance();
        }

        $this->line("\n");
    }
}
