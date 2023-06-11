<?php

namespace Corals\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {moduleName} {mainModel} {--modal}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module from Foo module';

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
     * @return void
     */
    public function handle()
    {
        $moduleName = strtolower($this->argument('moduleName'));
        $mainModel = strtolower($this->argument('mainModel'));

        $this->line(sprintf('Start Creating %s Module', ucfirst($moduleName)));

        $newModulePath = "Corals/modules/" . ucfirst($moduleName);

        if (File::isDirectory($newModulePath)) {
            $this->error('The module already exists,');

            if ($this->confirm('Do you want to delete the existing module and create a new?')) {
                $this->warn(sprintf('Deleting the %s Module', ucfirst($moduleName)));
                File::deleteDirectory($newModulePath);
            } else {
                $this->info('Nothing has changed, Enjoy.');
                return null;
            }
        }

        $this->line('Copying foo');

        File::copyDirectory("vendor/corals/foo/src", $newModulePath);

        $this->line('File/Folders Cleanup');

        if ($this->option('modal')) {
            File::delete("$newModulePath/Http/Controllers/BarsController.php");
            File::delete("$newModulePath/Models/Bar.php");
            File::delete("$newModulePath/Transformers/BarTransformer.php");
            File::deleteDirectory("$newModulePath/resources/views/bars");
            File::move("$newModulePath/resources/views/bazs", "$newModulePath/resources/views/$mainModel" . "s");
        } else {
            File::delete("$newModulePath/Http/Controllers/BazsController.php");
            File::delete("$newModulePath/Models/Baz.php");
            File::delete("$newModulePath/Transformers/BazTransformer.php");
            File::deleteDirectory("$newModulePath/resources/views/bazs");
            File::move("$newModulePath/resources/views/bars", "$newModulePath/resources/views/$mainModel" . "s");
        }

        $pattern = [];
        $replacement = [];

        $this->line('Set Module Naming convention.');

        foreach (['foo', 'bar', 'baz'] as $key) {
            $pattern[] = sprintf('/%s/', ucfirst($key));
            $pattern[] = sprintf('/%s/', lcfirst($key));

            if ($key == 'foo') {
                $object = $moduleName;
            } else {
                $object = $mainModel;
            }

            $replacement[] = ucfirst($object);
            $replacement[] = lcfirst($object);
        }

        $files = File::allFiles($newModulePath);

        foreach ($files as $file) {
            $content = file_get_contents($file);
            $content = preg_replace($pattern, $replacement, $content);
            file_put_contents($file, $content);

            $newPath = preg_replace($pattern, $replacement, $file->getPathname());
            rename($file->getPathname(), $newPath);
        }
        $this->line('Generate module.json');
        rename("$newModulePath/_module.json", "$newModulePath/module.json");

        $this->info('Module Created Enjoy');
    }
}
