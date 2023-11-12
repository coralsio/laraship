<?php namespace Corals\Theme\Commands;

use Corals\Theme\ThemeManifest;
use Madnest\Madzipper\Madzipper;

class createPackage extends baseCommand
{
    protected $signature = 'theme:package {themeName?}';
    protected $description = 'Create a theme package';
    protected $themes;


    public function handle()
    {
        $themeName = $this->argument('themeName');

        if ($themeName == "") {
            $themes = array_map(function ($theme) {
                return $theme->name;
            }, \Theme::all());
            $themeName = $this->choice('Select a theme to create a distributable package:', $themes);
        }

        if (!$this->themes) {
            $this->themes = \Modules::scanThemesJsonFiles();;

        }


        $theme = $this->themes[$themeName];

        $asset_path = \Modules::getThemesPublicPath() . $theme['assetPath'];
        $viewsPath = \Modules::getThemesBasedPath() . $theme['viewsPath'];
        $filename = env('PACKAGE_PATH') . "/" . $theme['name'] . ".zip";


        $zipper = new Madzipper();
        $zipper->make($filename);
        $zipper->folder($themeName . '/assets/' . $themeName)->add($asset_path);
        $zipper->folder($themeName . '/views/' . $themeName)->add($viewsPath);
        $zipper->close();

        $this->info("Package created at [$filename]");
    }


}
