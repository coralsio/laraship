<?php namespace Corals\Theme;

use Corals\Foundation\Facades\CoralsForm;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

class Themes
{

    protected $themesPath;
    protected $activeTheme = null;
    protected $themes = [];
    protected $laravelViewsPath;
    protected $cachePath;
    protected $filesystem;
    protected $tempPath;

    public function __construct()
    {
        $this->laravelViewsPath = Config::get('view.paths');
        $this->themesPath = Config::get('themes.themes_path', null) ?: Config::get('view.paths')[0];
        $this->cachePath = base_path('bootstrap/cache/themes.php');
        $this->filesystem = new Filesystem();
        $this->tempPath = $this->theme_packages_path('tmp');
    }

    /**
     * Return $filename path located in themes folder
     *
     * @param  string $filename
     * @return string
     */
    public function themes_path($filename = null)
    {
        return $filename ? $this->themesPath . '/' . $filename : $this->themesPath;
    }

    /**
     * Return list of registered themes
     *
     * @return array
     */
    public function all()
    {
        return $this->themes;
    }

    /**
     * Check if @themeName is registered
     *
     * @return bool
     */
    public function exists($themeName)
    {
        foreach ($this->themes as $theme) {
            if ($theme->name == $themeName)
                return true;
        }
        return false;
    }

    /**
     * @param $themeName
     * @return Theme
     * @throws Exceptions\themeNotFound
     */
    public function set($themeName)
    {
        if ($this->exists($themeName)) {
            $theme = $this->find($themeName);
        } else {
            $theme = new Theme($themeName);
        }

        $this->activeTheme = $theme;

        $this->addThemeViews($theme);

        Event::dispatch('corals.laravel-theme.change', $theme);

        return $theme;
    }

    public function addThemeViews($theme)
    {
        // Get theme view paths
        $paths = $theme->getViewPaths();

        // fall-back to default paths (set in views.php config file)
        foreach ($this->laravelViewsPath as $path) {
            if (!in_array($path, $paths)) {
                $paths[] = $path;
            }
        }

        Config::set('view.paths', $paths);

        foreach ($paths as $path) {
            \View::addLocation($path);
        }
    }

    /**
     * Get current theme
     *
     * @return Theme
     */
    public function current()
    {
        return $this->activeTheme ? $this->activeTheme : null;
    }

    /**
     * Get current theme's name
     *
     * @return string
     */
    public function get()
    {
        return $this->current() ? $this->current()->name : '';
    }

    /**
     * @param $themeName
     * @return mixed
     * @throws Exceptions\themeNotFound
     */
    public function find($themeName)
    {
        // Search for registered themes
        foreach ($this->themes as $theme) {
            if ($theme->name == $themeName)
                return $theme;
        }

        throw new Exceptions\themeNotFound($themeName);
    }

    /**
     * @param Theme $theme
     * @return Theme
     * @throws Exceptions\themeAlreadyExists
     */
    public function add(Theme $theme)
    {
        if ($this->exists($theme->name)) {
            throw new Exceptions\themeAlreadyExists($theme);
        }
        $this->themes[] = $theme;
        return $theme;
    }

    // Original view paths defined in config.view.php
    public function getLaravelViewPaths()
    {
        return $this->laravelViewsPath;
    }

    public function cacheEnabled()
    {
        return config('themes.cache', true);
    }

    /**
     * @throws \Exception
     */
    // Rebuilds the cache file
    public function rebuildCache()
    {
        $themes = $this->scanJsonFiles();
        // file_put_contents($this->cachePath, json_encode($themes, JSON_PRETTY_PRINT));

        $stub = file_get_contents(__DIR__ . '/stubs/cache.stub');
        $contents = str_replace('[CACHE]', var_export($themes, true), $stub);
        file_put_contents($this->cachePath, $contents);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    // Loads themes from the cache
    public function loadCache()
    {
        if (!file_exists($this->cachePath)) {
            $this->rebuildCache();
        }

        // $data = json_decode(file_get_contents($this->cachePath), true);

        $data = include($this->cachePath);

        if ($data === null) {
            throw new \Exception(trans('Theme::exception.theme.theme_invalid_cash_json', ['cachePath' => $this->cachePath]));
        }
        return $data;
    }

    /**
     * @return array
     * @throws \Exception
     */
    // Scans theme folders for theme.json files and returns an array of themes
    public function scanJsonFiles()
    {
        $themes = [];
        foreach (glob($this->themes_path('*'), GLOB_ONLYDIR) as $themeFolder) {
            $themeFolder = realpath($themeFolder);
            if (file_exists($jsonFilename = $themeFolder . '/' . 'theme.json')) {

                $folders = explode(DIRECTORY_SEPARATOR, $themeFolder);
                $themeName = end($folders);

                // default theme settings
                $defaults = [
                    'name' => $themeName,
                    'assetPath' => $themeName,
                    'extends' => null,
                ];

                // If theme.json is not an empty file parse json values
                $json = file_get_contents($jsonFilename);
                if ($json !== "") {
                    $data = json_decode($json, true);
                    if ($data === null) {
                        throw new \Exception(trans('Theme::exception.theme.theme_invalid_folder', ['themeFolder' => $themeFolder]));
                    }
                } else {
                    $data = [];
                }

                // We already know viewsPath since we have scaned folders.
                // we will overide this setting if exists
                $data['viewsPath'] = $themeName;

                $themes[] = array_merge($defaults, $data);
            }
        }
        return $themes;
    }

    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function loadThemesJson()
    {
        if ($this->cacheEnabled()) {
            return $this->loadCache();
        } else {
            return $this->scanJsonFiles();
        }
    }

    /**
     * Scan all folders inside the themes path & config/themes.php
     * If a "theme.json" file is found then load it and setup theme
     * @throws Exceptions\themeNotFound
     * @throws \Exception
     */
    public function scanThemes()
    {

        $parentThemes = [];
        $themesConfig = config('themes.themes', []);

        foreach ($this->loadThemesJson() as $data) {
            // Are theme settings overriden in config/themes.php?
            if (array_key_exists($data['name'], $themesConfig)) {
                $data = array_merge($data, $themesConfig[$data['name']]);
            }

            // Create theme
            $theme = new Theme(
                $data['name'],
                $data
            );

            // Has a parent theme? Store parent name to resolve later.
            if ($data['extends']) {
                $parentThemes[$theme->name] = $data['extends'];
            }

            // Load the rest of the values as theme Settings
            $theme->loadSettings($data);
        }

        // Add themes from config/themes.php
        foreach ($themesConfig as $themeName => $themeConfig) {
            // Is it an element with no values?
            if (is_string($themeConfig)) {
                $themeName = $themeConfig;
                $themeConfig = [];
            }

            // Create new or Update existing?
            if (!$this->exists($themeName)) {
                $theme = new Theme($themeName);
            } else {
                $theme = $this->find($themeName);
            }

            // Load Values from config/themes.php
            if (isset($themeConfig['assetPath'])) {
                $theme->assetPath = $themeConfig['assetPath'];
            }

            if (isset($themeConfig['viewsPath'])) {
                $theme->viewsPath = $themeConfig['viewsPath'];
            }

            if (isset($themeConfig['type'])) {
                $theme->type = $themeConfig['type'];
            }
            if (isset($themeConfig['caption'])) {
                $theme->caption = $themeConfig['caption'];
            }
            if (isset($themeConfig['version'])) {
                $theme->version = $themeConfig['version'];
            }
            if (isset($themeConfig['routeNamespace'])) {
                $theme->routeNamespace = $themeConfig['routeNamespace'];
            }
            if (isset($themeConfig['extends'])) {
                $parentThemes[$themeName] = $themeConfig['extends'];
            }

            $theme->loadSettings(array_merge($theme->settings, $themeConfig));
        }

        // All themes are loaded. Now we can assign the parents to the child-themes
        foreach ($parentThemes as $childName => $parentName) {
            $child = $this->find($childName);

            if (\Theme::exists($parentName)) {
                $parent = $this->find($parentName);
            } else {
                $parent = new Theme($parentName);
            }

            $child->setParent($parent);
        }
    }

    /*--------------------------------------------------------------------------
    | Proxy to current theme
    |--------------------------------------------------------------------------*/

    // Return url of current theme
    public function url($filename)
    {
        // If no Theme set, return /$filename
        if (!$this->current())
            return "/" . ltrim($filename, '/');

        return $this->current()->url($filename);
    }

    /**
     * Act as a proxy to the current theme. Map theme's functions to the Themes class. (Decorator Pattern)
     */
    public function __call($method, $args)
    {
        if (($theme = $this->current())) {
            return call_user_func_array(array($theme, $method), $args);
        } else {
            throw new \Exception(trans('Theme::exception.theme.theme_cannot_execute', ['method' => $method, 'self_class' => self::class]), 1);
        }
    }

    /*--------------------------------------------------------------------------
    | Blade Helper Functions
    |--------------------------------------------------------------------------*/

    /**
     * Return css link for $href
     *
     * @param  string $href
     * @param  string $id
     * @return string
     */
    public function css($href, $id = null)
    {
        if ($id) {
            return sprintf('<link media="all" type="text/css" rel="stylesheet" href="%s" id="%s">', $this->url($href), $id);
        } else {
            return sprintf('<link media="all" type="text/css" rel="stylesheet" href="%s">', $this->url($href));
        }
    }

    /**
     * Return script link for $href
     *
     * @param  string $href
     * @return string
     */
    public function js($href)
    {
        return sprintf('<script src="%s"></script>', $this->url($href));
    }

    /**
     * Return img tag
     *
     * @param  string $src
     * @param  string $alt
     * @param  string $Class
     * @param  array $attributes
     * @return string
     */
    public function img($src, $alt = '', $class = '', $attributes = array())
    {
        return sprintf('<img src="%s" alt="%s" class="%s" %s>',
            $this->url($src),
            $alt,
            $class,
            $this->HtmlAttributes($attributes)
        );
    }

    /**
     * Return attributes in html format
     *
     * @param  array $attributes
     * @return string
     */
    private function HtmlAttributes($attributes)
    {
        $formatted = join(' ', array_map(function ($key) use ($attributes) {
            if (is_bool($attributes[$key])) {
                return $attributes[$key] ? $key : '';
            }
            return $key . '="' . $attributes[$key] . '"';
        }, array_keys($attributes)));
        return $formatted;
    }

    public function formatThemeActions($theme, $type, $remote_updates)
    {
        $resource_url = config('themes.resource_url');
        $spacer = '&nbsp;';
        $actions = '';

        if ($theme->name != config('themes.corals_' . $type) && $theme->name != \Settings::get('active_' . $type . '_theme')) {
            $actions .= CoralsForm::link(url($resource_url . '/uninstall/' . $theme->name), 'Theme::labels.theme.theme_uninstall', [
                'class' => 'btn btn-danger btn-xs',
                'data' => [
                    'action' => 'delete',
                    'page_action' => 'site_reload'
                ]]);
        }

        if ($theme->name != \Settings::get('active_' . $type . '_theme')) {
            $actions .= $spacer . CoralsForm::link(url($resource_url . '/activate/' . $type . '/' . $theme->name), 'Theme::labels.theme.theme_activate', [
                    'class' => 'btn btn-success btn-xs',
                    'data' => [
                        'action' => 'post',
                        'confirmation' => trans('Theme::labels.theme.confirmation.activate_theme', ['name' => $theme->name]),
                        'page_action' => "site_reload"
                    ]
                ]);
        } elseif (config('themes.corals_' . $type) == \Settings::get('active_' . $type . '_theme')) {
            $actions .= $spacer . '<b><i class="fa fa-check-circle text-success"></i> Active</b>';
        } else {
            $actions .= $spacer . CoralsForm::link(url($resource_url . '/deactivate/' . $type . '/' . $theme->name), 'Theme::labels.theme.theme_deactivate', [
                    'class' => 'btn btn-warning btn-xs',
                    'data' => [
                        'action' => 'post',
                        'confirmation' => trans('Theme::labels.theme.confirmation.deactivate_theme', ['name' => $theme->name]),
                        'page_action' => "site_reload"
                    ]
                ]);
        }
        if ($theme->name == \Settings::get('active_' . $type . '_theme') && $type == 'frontend') {
            $actions .= $spacer . CoralsForm::link(url($resource_url . '/import-demo/' . $theme->name), 'Theme::labels.theme.theme_import_demo', [
                    'class' => 'btn btn-primary btn-xs',
                    'data' => [
                        'action' => 'post',
                        'confirmation' => trans('Theme::labels.theme.confirmation.import_theme', ['name' => $theme->name]),
                        'page_action' => "site_reload"
                    ]
                ]);
        }
        if (isset($remote_updates[$theme->name]) && version_compare($remote_updates[$theme->name]['version'], $theme->version, '>')) {
            $actions .= $spacer . CoralsForm::link(url($resource_url . '/download-update/' . $theme->name), trans('Theme::labels.theme.theme_new', ['version' => $remote_updates[$theme->name]['version']]), [
                    'class' => 'btn btn-primary btn-xs',
                    'data' => [
                        'action' => 'post',
                        'confirmation' => trans('Theme::labels.theme.confirmation.download_update_theme', ['name' => $theme->name]),
                        'page_action' => "site_reload"
                    ]
                ]);
        }

        return $actions;
    }

    /**
     * @param $filename
     * @param $update_if_exist
     * @throws \Exception
     */
    public function installTheme($filename, $update_if_exist)
    {
        $themeTempPath = $this->tempPath . '/' . $filename;

        \Madzipper::make($themeTempPath)->extractTo($this->tempPath);

        \Madzipper::close();

        $tempFiles = scandir($this->tempPath);
        foreach ($tempFiles as $file) {
            if (is_dir($this->tempPath . '/' . $file) && !in_array($file, ['.', '..'])) {
                $filename = $file;
            }
        }

        $themeTempPath = $this->tempPath . '/' . $filename;
        if (!$this->isValidThemeStructure($themeTempPath)) {
            throw new \Exception(trans('Theme::exception.theme.theme_invalid_structure'));
        }

        $themeJson = new ThemeManifest();

        $themeJson->loadFromFile($themeTempPath . '/views/' . basename($themeTempPath) . "/theme.json");

        // Check if theme is already installed
        $themeName = $themeJson->get('name');
        $themeVersion = $themeJson->get('version');

        $isThemeInstalled = $this->theme_installed($themeName);

        if ($isThemeInstalled && !$update_if_exist) {
            throw new \Exception(trans('Theme::exception.theme.theme_invalid_installed', ['themeName' => $themeName]));
        }

        $installedThemeVersion = null;

        if ($isThemeInstalled) {
            $installedTheme = $this->find($themeName);
            $installedThemeVersion = $installedTheme->version;
        }

        // Target Paths
        $viewsPath = themes_path($themeJson->get('viewsPath'));
        $assetPath = public_path($themeJson->get('assetPath'));

        $this->filesystem->copyDirectory($themeTempPath . '/views/' . basename($themeTempPath), $viewsPath . '/' . basename($themeTempPath));
        $this->filesystem->copyDirectory($themeTempPath . '/assets/' . basename($themeTempPath), $assetPath);


        if ($isThemeInstalled) {

            $files = \File::glob($viewsPath . '/' . basename($themeTempPath) . '/update-batches/*.php');
            $batches = [];
            foreach ($files as $batch) {
                $batches[basename($batch, '.php')] = $batch;
            }
            $this->executeThemeBatches($installedThemeVersion, $themeVersion, $batches);

        }


        $this->filesystem->deleteDirectory($themeTempPath);
        $this->filesystem->delete($filename);
    }


    private function executeThemeBatches($installedThemeVersion, $themeVersion, $batches)
    {
        foreach ($batches as $version => $batch) {
            if (version_compare($themeVersion, $installedThemeVersion, '>')) {
                require $batch;
            }
        }
    }


    protected function isValidThemeStructure($themeTempPath)
    {
//        logger($themeTempPath);
//        logger($this->filesystem->exists($themeTempPath) ? 'themeTempPath' : '---');
//        logger($themeTempPath . '/assets/' . basename($themeTempPath));
//        logger($this->filesystem->exists($themeTempPath . '/assets/' . basename($themeTempPath)) ? 'assets' : '---');
//        logger($themeTempPath . '/views/' . basename($themeTempPath));
//        logger($this->filesystem->exists($themeTempPath . '/views/' . basename($themeTempPath)) ? 'assets' : '---');

        return $this->filesystem->exists($themeTempPath)
            && $this->filesystem->exists($themeTempPath . '/assets/' . basename($themeTempPath))
            && $this->filesystem->exists($themeTempPath . '/views/' . basename($themeTempPath));
    }

    public function createTempFolder()
    {
        $this->clearTempFolder();
        $this->filesystem->makeDirectory($this->tempPath);
    }

    public function clearTempFolder()
    {
        if ($this->filesystem->exists($this->tempPath)) {
            \File::deleteDirectory($this->tempPath);
        }
    }

    public function theme_packages_path($path = '')
    {
        if (!$this->filesystem->exists(storage_path("themes"))) {
            $this->filesystem->makeDirectory(storage_path("themes"));
        }

        return storage_path("themes/$path");
    }

    public function theme_installed($themeName)
    {
        if (!\Theme::exists($themeName)) {
            return false;
        }

        $viewsPath = \Theme::find($themeName)->viewsPath;
        return $this->filesystem->exists(themes_path("$viewsPath/theme.json"));
    }


    public function theme_view_exists($themeName,$view)
    {
        if (!\Theme::exists($themeName)) {
            return false;
        }
        $viewsPath = \Theme::find($themeName)->viewsPath;
        return $this->filesystem->exists(themes_path("$viewsPath/{$view}.blade.php"));
    }


}
