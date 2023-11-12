<?php namespace Corals\Theme\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;

class installPackage extends baseCommand
{
    protected $signature = 'theme:install {package?}';
    protected $description = 'Install a theme package';

    public function handle()
    {
        $package = $this->argument('package');

        if (!$package) {
            $filenames = $this->files->glob($this->packages_path('*.zip'));
            $packages = array_map(function ($filename) {
                return basename($filename, '.zip');
            }, $filenames);
            $package = $this->choice('Select a theme to install:', $packages);
        }
        $package = $this->packages_path($package . '.zip');

        // Create Temp Folder
        $this->createTempFolder();


        \Madzipper::make($package)->extractTo($this->tempPath);

        \Madzipper::close();


        // Read theme.json
        $themeJson = new \Corals\Theme\ThemeManifest();
        $themeJson->loadFromFile("{$this->tempPath}/" . basename($package, '.zip') . '/views/' . basename($package, '.zip') . '/theme.json');

        // Check if theme is already installed
        $themeName = $themeJson->get('name');
        if ($this->theme_installed($themeName)) {
            $this->error('Error: Theme ' . $themeName . ' already exist. You must remove it first with "artisan theme:remove ' . $themeName . '"');
            $this->clearTempFolder();
            return;
        }

        // Target Paths
        $viewsPath = themes_path($themeJson->get('viewsPath')) . '/' . basename($package, '.zip');
        $assetPath = public_path($themeJson->get('assetPath'));

        // If Views+Asset paths don't exist, move theme from temp to target paths
        if (file_exists($viewsPath)) {
            $this->info("Warning: Views path [$viewsPath] already exists. Will not be installed.");
        } else {
            $tempViewThemPath = "{$this->tempPath}/" . basename($package, '.zip') . '/views/' . basename($package, '.zip');
            exec("mv $tempViewThemPath $viewsPath");

            // Remove 'theme-views' from theme.json
            $themeJson->remove('viewsPath');
            $themeJson->saveToFile("$viewsPath/theme.json");
            $this->info("Theme views installed to path [$viewsPath]");
        }

        if (file_exists($assetPath)) {
            $this->error("Error: Asset path [$assetPath] already exists. Will not be installed.");
        } else {
            $tempAssetThemPath = "{$this->tempPath}/" . basename($package, '.zip') . '/assets/' . basename($package, '.zip');
            exec("mv $tempAssetThemPath $assetPath");
            $this->info("Theme assets installed to path [$assetPath]");
        }

        // Rebuild Themes Cache
        \Theme::rebuildCache();

        // Del Temp Folder
        $this->clearTempFolder();
    }


}
