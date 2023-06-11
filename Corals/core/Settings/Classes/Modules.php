<?php

namespace Corals\Settings\Classes;

use Corals\Foundation\Facades\Actions;
use Corals\Settings\Models\Module;
use GuzzleHttp\TransferStats;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

/**
 * Class Modules
 * @package Corals\Settings\Classes
 */
class Modules
{
    protected $modules_packages = [];

    public function __construct()
    {
    }

    /**
     * @param $path
     * @return string
     */
    public function getModulesBasePath($path)
    {
        return base_path($path);
    }

    /**
     * @param $path
     * @return array
     */
    public function getPathFolders($path)
    {
        if (!File::exists($path)) {
            return [];
        }
        return File::directories($path);
    }

    /**
     * @param string $path
     * @return string
     */
    public function getFileData($path)
    {
        if (!File::exists($path) || !File::isFile($path)) {
            return null;
        }

        return File::get($path, true);
    }

    public function clearModulesSettingsCache()
    {
        \Cache::forget('module_settings_list');
    }


    public function addModulesPackages($packageName)
    {
        $modulePackage = $this->getModulesBasePath('vendor/' . $packageName);


        $this->modules_packages [] = $modulePackage;

    }

    public function getModulesPackages()
    {
        return $this->modules_packages;
    }

    /**
     * @param null $requested_code
     * @return array|\Illuminate\Support\Collection
     */
    public function getModulesSettings($requested_code = null)
    {
        $modules = Cache::remember('module_settings_list', config('corals.cache_ttl'), function () {
            $modules = [];

            foreach (config('settings.models.module.paths') as $path) {
                $modules = array_merge($modules, $this->getPathFolders($this->getModulesBasePath($path)));
            }
            $modules = array_merge($modules, $this->getModulesPackages());
            $modulesArray = [];

            foreach ($modules as $row) {
                $file = $row . '/module.json';

                $data = json_decode($this->getFileData($file), true);
                if ($data === null || !is_array($data)) {
                    continue;
                }

                $code = Arr::get($data, 'code');

                $module = Module::where('code', $code)->first();

                $namespace = trim(str_replace('\\\\', '\\', $data['namespace']), '\\');

                if (!$module) {
                    $type = Arr::get($data, 'type');
                    $folder = Arr::get($data, 'folder');
                    $provider = Arr::get($data, 'provider', null);

                    if ($provider) {
                        $provider = $namespace . '\\' . $provider;
                    }

                    $params = [
                        'code' => $code,
                        'load_order' => Arr::get($data, 'load_order', 0),
                        'type' => $type,
                        'folder' => $folder,
                        'provider' => $provider
                    ];

                    if ($type == 'core') {
                        $params['enabled'] = 1;
                        $params['installed'] = 1;
                        $params['installed_version'] = Arr::get($data, 'version');
                    }

                    $module = Module::create($params);
                }

                $data['enabled'] = $module->enabled;
                $data['folder'] = $module->folder;
                $data['installed'] = $module->installed;
                $data['installed_version'] = $module->installed_version;
                $data['hashed_id'] = $module->hashed_id;
                $data['notes'] = $module->notes;
                $data['license_key'] = $module->license_key;
                $data['folder'] = $module->folder;
                $data['type'] = $module->type;
                $data['namespace'] = $namespace;
                $data['provider'] = $module->provider;
                $data['type_formatted'] = $this->getModuleFormattedType($module->type);
                $object = (object)$data;

                array_push($modulesArray, $object);
            }

            return $modules = collect($modulesArray);
        });

        if ($requested_code) {
            return $module = $modules->where('code', $requested_code)->first();
        } else {
            return $modules;
        }
    }

    /**
     * @param $currentVersion
     * @param $condition ^1.2, ~1.0, 1.5
     * @return bool
     */
    public function modulesVersionCompare($currentVersion, $condition)
    {
        if (!$condition) {
            return true;
        }
        //get first letter
        $where = substr($condition, 0, 1);
        switch ($where) {
            case '^':
                $operator = '>=';
                $version = substr($condition, 1);
                break;
            case '~':
                $operator = '<=';
                $version = substr($condition, 1);
                break;
            default:
                $version = $condition;
                $operator = '==';
        }

        return version_compare($currentVersion, $version, $operator);
    }

    /**
     * @param array $module
     * @param $condition
     * @return array
     */
    public function modulesVersionCompareMessage($module, $condition)
    {
        if (!$condition) {
            return ['error' => trans('Settings::labels.module.error')];
        }

        $where = substr($condition, 0, 1);

        switch ($where) {
            case '^':
                $message = trans('Settings::labels.message.version_must_higher',
                    ['name' => $module->code, 'version' => substr($condition, 1)]);
                break;
            case '~':
                $message = trans('Settings::labels.message.version_must_lower_or_equal',
                    ['name' => $module->code, 'version' => substr($condition, 1)]);
                break;
            default:
                $message = trans('Settings::labels.message.version_must_equal',
                    ['name' => $module->code, 'version' => $where]);
        }

        $result = $this->modulesVersionCompare($module->installed_version ?? 0, $condition);

        if (!$result) {
            return ['error' => $message];
        }

        return ['success' => trans('Settings::labels.message.version_ok')];
    }

    /**
     * @param $moduleNeedToCheck
     * @return array
     */
    function checkModuleRequire($moduleNeedToCheck)
    {
        $required = property_exists($moduleNeedToCheck, 'require') ? $moduleNeedToCheck->require : [];

        $messages = [];

        $error = false;

        foreach ($required as $moduleCode => $version) {
            $module = \Modules::getModulesSettings($moduleCode);

            if (!$module || !$module->installed || !$module->enabled) {
                $messages[] = trans('Settings::labels.message.missing_required_module', ['name' => $moduleCode]);
                $error = true;
                continue;
            }

            $moduleVersionCompare = $this->modulesVersionCompareMessage($module, $version);

            if (isset($moduleVersionCompare['error'])) {
                array_push($messages, $moduleVersionCompare['error']);
                $error = true;
            }
        }

        if ($error) {
            return ['error' => join(';<br/>', $messages)];
        } else {
            return ['success' => trans('Settings::labels.message.module_success_ok')];
        }
    }

    /**
     * @param $moduleNeedToCheck
     * @return array
     */
    function checkModuleRequired($module)
    {
        $enabled_modules = Module::where('installed', 1)->get();
        $modules = \Modules::getModulesSettings();

        foreach ($enabled_modules as $enabled_module) {
            $enabled_module_info = $modules->where('code', $enabled_module->code)->first();
            if ($enabled_module_info) {
                $required = property_exists($enabled_module_info, 'require') ? $enabled_module_info->require : [];
                foreach ($required as $moduleCode => $version) {
                    if ($moduleCode == $module->code) {
                        throw new \Exception(trans('Settings::exception.module.module_required',
                            ['required_module' => $module->code, 'module' => $enabled_module->code]));
                    }
                }
            }
        }
    }

    /**
     * @param $module
     * @param array $remote_updates
     * @return string
     */
    public function getModuleAction($module, $remote_updates = [])
    {
        $actions = '';

        if ($module->installed && $module->type != 'core') {
            $actions .= $this->formatAction($module, 'uninstall');

            if ($module->enabled) {
                $actions .= $this->formatAction($module, 'disable');
            } else {
                $actions .= $this->formatAction($module, 'enable');
            }
        } elseif ($module->type != 'core') {
            $result = $this->checkModuleRequire($module);
            if (isset($result['error'])) {
                $actions .= '<span class="label label-warning badge badge-warning"><i class="fa fa-info-circle"></i> ' . $result['error'] . '</span>';
            } else {
                $actions .= $this->formatAction($module, 'install');
            }
        }

        if ($module->installed && !is_null($module->installed_version) && version_compare($module->version,
                $module->installed_version, '>')) {
            $actions .= $this->formatAction($module, 'update');
        }
        if (array_key_exists($module->code,
                $remote_updates) && version_compare($remote_updates[$module->code]['version'], $module->version, '>')) {
            $actions .= $this->formatAction($module, 'download', $remote_updates[$module->code]);
        }
        if ($module->installed && $module->type != 'core') {
            $url = config('settings.models.module.resource_url') . '/' . $module->hashed_id . '/license-key';
            $actions .= '<a href="' . $url . '" class="btn btn-xs btn-primary m-r-5 m-l-5 modal-load" data-title = ' . trans('Settings::labels.module.update_license_key') . '><i class="fa fa-key"></i></a>';
        }
        return $actions;
    }

    protected function formatAction($module, $action, $remote_version = [])
    {
        $module_url = config('settings.models.module.resource_url') . '/' . $module->hashed_id;
        $class = '';
        $label = '';
        $need_confirmation = true;
        $url = url($module_url . '/' . $action);
        switch ($action) {
            case 'install':
                $label = trans('Settings::labels.module.install');
                $class = 'btn-success';
                $need_confirmation = false;
                break;
            case  'uninstall':
                $label = trans('Settings::labels.module.uninstall');
                $class = 'btn-danger';
                break;
            case 'disable':
                $label = trans('Settings::labels.module.disable');
                $class = 'btn-warning';
                break;
            case 'enable':
                $label = trans('Settings::labels.module.enable');
                $class = 'btn-success';
                $need_confirmation = false;
                break;
            case 'update':
                $label = trans('Settings::labels.module.update');
                $class = 'btn-primary';
                break;
            case 'download':
                $label = trans('Settings::labels.module.new_version', ['name' => $remote_version['version']]);
                $class = 'btn-primary';
                break;
        }

        return '<a href="' . $url . '" class="btn btn-xs ' . $class . ' m-r-5" data-style="slide-right"
            data-action="post" '
            . ($need_confirmation ? ('data-confirmation="You are going to ' . $action . ' ' . $module->name . '."') : '')
            . ' data-page_action="site_reload">' . $label . '</a>';
    }

    /**
     * @param $type
     * @return string
     */
    protected function getModuleFormattedType($type)
    {
        $class = 'label label-';
        switch ($type) {
            case 'core':
                $class = $class . 'default';
                break;
            case 'module':
                $class = $class . 'info';
                break;
            case 'payment':
                $class = $class . 'primary';
                break;
        }

        return '<span class="' . $class . '">' . strtoupper($type) . '</span>';
    }

    /**
     * @param $moduleCode
     * @param $batches
     * @throws \Exception
     */
    public function executeModuleBatches($moduleCode, $batches)
    {
        $currentModuleInformation = $this->getModulesSettings($moduleCode);

        if (!$currentModuleInformation) {
            throw new \Exception(trans('Settings::exception.module.invalid_module', ['name' => $moduleCode]));
        }

        if ($currentModuleInformation->provider && class_exists($currentModuleInformation->provider)) {
            app()->register($currentModuleInformation->provider);
        }

        $installedModuleVersion = $currentModuleInformation->installed_version;

        foreach ($batches as $version => $batch) {
            if (!$installedModuleVersion || version_compare($version, $installedModuleVersion, '>')) {
                require $batch;
            }
        }
    }

    /**
     * @param $module
     * @param bool $clearCache
     * @return mixed
     * @throws \Exception
     */
    public function normalizeDatabaseModule($module, $clearCache = true)
    {
        if ($clearCache) {
            $cacheCommands = ['cache:clear', 'config:clear', 'view:clear', 'route:clear', 'clear-compiled'];

            foreach ($cacheCommands as $command) {
                Artisan::call($command);
            }
        }
        if ($module instanceof Module) {
            return $module;
        } else {
            $module = Module::where('code', $module)->first();
            if (!$module) {
                throw new \Exception(trans('Settings::exception.module.module_not_exist'));
            }
            return $module;
        }
    }


    /**
     * @param $module
     * @throws \Exception
     */
    public function install($module)
    {
        try {
            Actions::do_action('pre_install_module', $module);
            config()->set('activitylog.enabled', false);
            $module = $this->normalizeDatabaseModule($module);
            $this->clearNotes($module);

            $moduleSettings = $this->getModulesSettings($module->code);

            if (!$moduleSettings) {
                throw new \Exception(trans('Settings::exception.module.module_not_exist'));
            }

            if ($module->installed) {
                throw new \Exception(trans('Settings::exception.module.module_install', ['name' => $module->code]));
            }

            if (property_exists($moduleSettings, 'provider') && !empty($moduleSettings->provider)) {
                $provider = $moduleSettings->provider;

                if (class_exists($provider)) {
                    app()->resolveProvider($provider)->registerPackage();
                    app()->resolveProvider($provider)->bootPackage();
                    app()->register($provider);
                } else {
                    throw new \Exception(trans('Settings::exception.module.module_provider_not_available'));
                }
            }

            $install_provider = $moduleSettings->namespace . '\Providers\InstallModuleServiceProvider';

            if (class_exists($install_provider)) {
                app()->register($install_provider);
            } else {
                throw new \Exception(trans('Settings::exception.module.provider_not_available'));
            }
            //$this->refreshComposerAutoload();

            $module->update([
                'installed' => true,
                'installed_version' => $moduleSettings->version,
                'enabled' => true,
                'notes' => null
            ]);

            $this->grantSuperuserRoleFullAccess();
            config()->set('activitylog.enabled', true);
            Actions::do_action('post_install_module', $module);
        } catch (\Exception $e) {
            config()->set('activitylog.enabled', true);
            report($e);
            throw new \Exception(trans('Settings::exception.module.install_module_failed',
                ['message_exception' => $e->getMessage()]));
        }
    }

    /**
     * @param $module
     * @throws \Exception
     */
    public function update($module)
    {
        try {
            Actions::do_action('pre_update_module', $module);
            config()->set('activitylog.enabled', false);

            $module = $this->normalizeDatabaseModule($module);

            update_morph_columns();

            $this->clearNotes($module);

            $moduleSettings = $this->getModulesSettings($module->code);

            $result = $this->checkModuleRequire($moduleSettings);
            if (isset($result['error'])) {
                throw new \Exception($result['error']);
            }


            if (!$moduleSettings) {
                throw new \Exception(trans('Settings::exception.module.module_not_exist'));
            }

            $update_provider = $moduleSettings->namespace . '\Providers\UpdateModuleServiceProvider';

            if (class_exists($update_provider)) {
                app()->register($update_provider);
            } else {
                throw new \Exception(trans('Settings::exception.module.update_provider_not_available'));
            }
            //$this->refreshComposerAutoload();

            $module->update([
                'installed' => true,
                'installed_version' => $moduleSettings->version,
                'notes' => null
            ]);

            $this->grantSuperuserRoleFullAccess();
            config()->set('activitylog.enabled', true);
            Actions::do_action('post_update_module', $module);
        } catch (\Exception $e) {
            report($e);
            config()->set('activitylog.enabled', true);
            throw $e;
        }
    }

    public function grantSuperuserRoleFullAccess()
    {
        $role = \Corals\User\Models\Role::findByName('superuser');
        $role->syncPermissions(\Spatie\Permission\Models\Permission::all());
    }

    /**
     * @param $module
     * @throws \Exception
     */
    public function downloadRemote($module)
    {
        Actions::do_action('pre_download_module', $module);

        $module_backup_file = "";
        $module = $this->normalizeDatabaseModule($module);
        $this->clearNotes($module);


        $moduleSettings = $this->getModulesSettings($module->code);
        $module_paths = config('settings.models.module.paths');

        $this->clearModulesSettingsCache();

        $module_path = base_path($module_paths[$moduleSettings->type] . "/" . $moduleSettings->folder);

        if (!$moduleSettings) {
            throw new \Exception(trans('Settings::exception.module.module_not_exist'));
        }

        try {
            $module_details = $this->getPluginDetails($module->code, $module->license_key);
            $module_details = (object)$module_details;
            $result = $this->checkModuleRequire($module_details);

            if (isset($result['error'])) {
                throw new \Exception($result['error']);
            }
            $downloaded_file = $this->download($module->code, $module->license_key);
            $module_backup_file = $this->backup($module, $moduleSettings, $module_path);
            if ($module->code != "corals-payment") {
                \File::deleteDirectory($module_path);
            }
            \Madzipper::make($downloaded_file)->extractTo(base_path($module_paths[$moduleSettings->type]),
                array($moduleSettings->folder), 1);
        } catch (\Exception $e) {
            if ($module_backup_file) {
                $this->restore($module_backup_file, $module_path);
            }
            throw new \Exception(trans('Settings::exception.module.download_module_failed',
                ['message_exception' => $e->getMessage()]));
        }

        // remove the downloaded module from the list
        $remote_updates = \Cache::get('modules_remote_updates', []);
        unset($remote_updates[$module->code]);
        \Cache::put('modules_remote_updates', $remote_updates, config('corals.cache_ttl'));

        Actions::do_action('post_download_module', $module);
    }

    /**
     * @param $module_key
     * @param $module_license_key
     * @param $path
     * @throws \Exception
     */
    public function downloadNew($module_key, $module_license_key)
    {
        Actions::do_action('pre_download_new_module', $module_key);

        $module = Module::where('code', $module_key)->first();
        if ($module) {
            throw new \Exception(trans('Settings::exception.module.already_install'));
        }

        $module_details = $this->getPluginDetails($module_key, $module_license_key);

        $module_paths = config('settings.models.module.paths');
        $path = $module_paths[$module_details['type']];

        //$this->refreshComposerAutoload();


        $this->clearModulesSettingsCache();

        try {
            $downloaded_file = $this->download($module_key, $module_license_key);
            \Madzipper::make($downloaded_file)->extractTo(base_path($path));
        } catch (\Exception $e) {
            throw new \Exception(trans('Settings::exception.module.download_module_failed',
                ['message_exception' => $e->getMessage()]));
        }

        Actions::do_action('post_download__new_module', $module_key);
    }


    /**
     * @param $module_key
     * @param $module_license_key
     * @param null $tempFolder
     * @return string
     * @throws \Exception
     */
    public function download($module_key, $module_license_key, $tempFolder = null)
    {
        $updater_url = config('settings.models.module.updater_url');
        $updater_tmp_path = $tempFolder ?? config('settings.models.module.tmp_path');
        $license_key = config('settings.models.module.license_key');


        $filename_tmp = $updater_tmp_path . '/' . $module_key . ".zip";
        $url = url('');
        $plugin_download_url = "{$updater_url}?laravel_version=" . app()->version() . "&action=downloadPlugin&license_key={$license_key}&plugin_code={$module_key}&plugin_license_key=" . $module_license_key . '&domain=' . $url;

        $newUpdate = file_get_contents($plugin_download_url);

        if ($result = json_decode($newUpdate, true)) {
            if (isset($result['status']) && $result['status'] == "error") {
                throw new \Exception($result['message']);
            }
        }

        if (isset($newUpdate['status']) && $newUpdate['status'] == "error") {
            throw new \Exception($newUpdate['message']);
        }

        $dlHandler = fopen($filename_tmp, 'w');

        fwrite($dlHandler, $newUpdate);

        if (is_file($filename_tmp)) {
            if (!fwrite($dlHandler, $newUpdate)) {
                throw new \Exception(trans('Settings::exception.module.could_not_save'));
            }
        } else {
            throw new \Exception(trans('Settings::exception.module.could_not_download'));
        }
        fclose($dlHandler);
        return $filename_tmp;
    }

    /**
     * @param $module
     * @param $moduleSettings
     * @param $module_path
     * @return string
     * @throws \Exception
     */
    public function backup($module, $moduleSettings, $module_path)
    {
        try {
            $updater_tmp_path = config('settings.models.module.tmp_path');
            $filename_backup = $updater_tmp_path . '/' . $module->code . "-" . date('YmdHis') . ".zip";
            $zipper = new \Madnest\Madzipper\Madzipper;
            $zipper->make($filename_backup)->folder($moduleSettings->folder)->add($module_path);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return $filename_backup;
    }

    /**
     * @param $module_backup_file
     * @param $module_path
     * @return bool
     * @throws \Exception
     */
    public function restore($module_backup_file, $module_path)
    {
        try {
            \Madzipper::make($module_backup_file)->extractTo($module_path);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return true;
    }

    /**
     * @param $module
     * @throws \Exception
     */
    public function uninstall($module)
    {
        try {
            $module = $this->normalizeDatabaseModule($module);

            $this->checkModuleRequired($module);

            Actions::do_action('pre_uninstall_module', $module);

            config()->set('activitylog.enabled', false);

            $this->clearNotes($module);

            $moduleSettings = $this->getModulesSettings($module->code);

            if (!$moduleSettings) {
                throw new \Exception(trans('Settings::exception.module.module_not_exist'));
            }

//        if (!$module->installed) {
//            throw new \Exception("Module " . $module->code . " not installed yet.");
//        }

            if (!$module->type == "core") {
                throw new \Exception("Module " . $module->code . " is core module");
            }

            $uninstall_provider = $moduleSettings->namespace . '\Providers\UninstallModuleServiceProvider';

            if (class_exists($uninstall_provider)) {
                app()->register($uninstall_provider);
            } else {
                throw new \Exception(trans('Settings::exception.module.uninstall_provider_not_available'));
            }

            //$this->refreshComposerAutoload();

            $module->update([
                'installed' => false,
                'enabled' => false,
                'installed_version' => null,
                'notes' => null
            ]);

            config()->set('activitylog.enabled', true);
            Actions::do_action('post_uninstall_module', $module);
        } catch (\Exception $e) {
            report($e);
            config()->set('activitylog.enabled', true);
            throw $e;
        }
    }

    /**
     * @param $module
     * @return bool
     */
    public function isModuleActive($module)
    {
        try {
            $module = $this->normalizeDatabaseModule($module, false);
        } catch (\Exception $exception) {
            return false;
        }

        if ($module->installed && $module->enabled) {
            return true;
        }

        return false;
    }

    /**
     * @param $module
     */
    private function clearNotes($module)
    {
        $module->notes = "";
        $module->save();
    }

    /**
     * Run command composer dump-autoload
     */
    public function refreshComposerAutoload()
    {
        $composer = app()['composer'];
        $composer->dumpOptimized();
    }

    /**
     * @param $request
     * @param array $modules
     * @param $cacheKey
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkForUpdates($request, $modules = [], $cacheKey = null)
    {
        $disable_update = config('settings.models.module.disable_update');
        if ($disable_update) {
            return ['remote_updates' => [], 'has_updates' => false];
        }
        $remote_updates = \Cache::get($cacheKey, []);
        $check_updates_result = ['message' => '', 'status' => '', 'has_updates' => false];

        if (isset($request['check-for-updates'])) {
            $updater_url = config('settings.models.module.updater_url');
            $license_key = config('settings.models.module.license_key');
            try {
                $client = new \GuzzleHttp\Client();
                $res = $client->request('GET', $updater_url, [
                    'query' => [
                        'license_key' => $license_key,
                        'domain' => url('/'),
                        'laravel_version' => app()->version(),
                        'installed_modules' => $modules,
                        'action' => 'checkForUpdates'
                    ],
                    'on_stats' => function (TransferStats $stats) use (&$url) {
                        $url = $stats->getEffectiveUri();
                    }
                ]);

                $check_updates_result = json_decode((string)$res->getBody()->getContents(), 1);
                //$check_updates_result = json_decode($res->getBody(), 1);

                if ($check_updates_result['message'] && $check_updates_result['status']) {
                    flash($check_updates_result['message'], $check_updates_result['status']);
                }

                if (isset($check_updates_result['status']) && $check_updates_result['status'] != "error") {
                    if (isset($check_updates_result['updates']) && count($check_updates_result['updates']) > 0) {
                        $remote_updates = $check_updates_result['updates'];
                    }
                    if (!$check_updates_result['has_updates']) {
                        flash(trans('Settings::labels.module.up_to_date'))->success();
                    }
                }


                $this->clearModulesSettingsCache();

                \Cache::put($cacheKey, $remote_updates, config('corals.cache_ttl'));
            } catch (\Exception $exception) {
                flash(trans('Settings::labels.module.error_contact'))->warning();
            }
        }

        return [
            'remote_updates' => $remote_updates,
            'message' => $check_updates_result['message'],
            'status' => $check_updates_result['status'],
            'has_updates' => $check_updates_result['has_updates'] ?? false
        ];
    }

    public function getPluginDetails($module_key, $module_license_key)
    {
        $updater_url = config('settings.models.module.updater_url');
        $license_key = config('settings.models.module.license_key');

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $updater_url, [
            'query' => [
                'license_key' => $license_key,
                'plugin_license_key' => $module_license_key,
                'laravel_version' => app()->version(),
                'plugin_code' => $module_key,
                'action' => 'getPluginDetails'
            ]
        ]);
        $plugin_details = json_decode($res->getBody(), 1);
        if ($plugin_details['status'] == "error") {
            throw new \Exception($plugin_details['message']);
        }
        $module_paths = config('settings.models.module.paths');

        if (!is_array($plugin_details) || !$plugin_details['type'] || !array_key_exists($plugin_details['type'],
                $module_paths)) {
            throw new \Exception(trans('Settings::exception.module.module_invalid'));
        }
        return $plugin_details;
    }

    public function hasUpdates()
    {
        $module_updates_count = Cache::remember('module_updates_count', config('corals.cache_ttl'), function () {
            $modules = $this->getModulesSettings();

            $installed_modules = $modules->map(function ($item) {
                return ['version' => $item->version, 'code' => $item->code, 'license_key' => $item->installed_version];
            });


            $installed_modules = $installed_modules->toArray();

            $has_updates = false;

            try {
                $check_updates_result = \Modules::checkForUpdates(['check-for-updates' => true], $installed_modules,
                    'modules_remote_updates');
                $has_updates = $check_updates_result['has_updates'] ?? false;
            } catch (\Exception $exception) {
                log_exception($exception, 'Modules', 'hasUpdates');
            }

            return $has_updates;
        });
        return $module_updates_count;
    }
}
