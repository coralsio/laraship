<?php

namespace Corals\Settings\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Settings\Facades\Modules;
use Corals\Settings\Http\Requests\ModuleRequest;
use Corals\Settings\Models\Module;
use Illuminate\Http\Request;

class ModulesController extends BaseController
{
    public function __construct()
    {
        $this->resource_url = config('settings.models.module.resource_url');
        $this->title = 'Settings::module.module.title';
        $this->title_singular = 'Settings::module.module.title_singular';

        parent::__construct();
    }

    /**
     * @param ModuleRequest $request
     * @return mixed
     */
    public function index(ModuleRequest $request)
    {
        if ($request->is('modules/rescan')) {
            Modules::clearModulesSettingsCache();
            return redirect('modules');
        }

        $modules = Modules::getModulesSettings();

        $installed_modules = $modules->map(function ($item) {
            return ['version' => $item->version, 'code' => $item->code, 'license_key' => $item->installed_version];
        });

        $installed_modules = $installed_modules->toArray();
        $remote_updates = [];
        $has_updates = false;

        try {
            $check_updates_result = Modules::checkForUpdates($request->all(), $installed_modules, 'modules_remote_updates');
            $remote_updates = $check_updates_result['remote_updates'];
            $has_updates = $check_updates_result['has_updates'];

            \Cache::put('module_updates_count', $check_updates_result['has_updates'], 60);

        } catch (\Exception $exception) {
            log_exception($exception, 'ModulesController', 'index');
        }

        return view('Settings::modules.index')->with(compact('modules', 'remote_updates', 'has_updates'));
    }

    /**
     * @param ModuleRequest $request
     * @return mixed
     */
    public function add(ModuleRequest $request)
    {
        return view('Settings::modules.add');
    }

    /**
     * @param ModuleRequest $request
     * @param Module $module
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function install(ModuleRequest $request, Module $module)
    {
        try {
            Modules::install($module);

            $message = ['level' => 'success', 'message' => trans('Settings::labels.message.module_install')];
        } catch (\Exception $exception) {
            $exceptionMessage = $exception->getMessage();
            $module->update(['notes' => $exception->getMessage()]);
            $message = $this->uninstallAction($module, false);
            $uninstallMessage = \Arr::get($message, 'message');
            $exceptionMessage = 'An error occurred during installation!<br/>' . $uninstallMessage
                . '<br/>' . $exceptionMessage;

            log_exception($exception, Module::class, 'install', $exceptionMessage);
            $message = ['level' => 'error', 'message' => $exceptionMessage];
        }

        return response()->json($message);
    }

    /**
     * @param ModuleRequest $request
     * @param Module $module
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(ModuleRequest $request, Module $module)
    {
        try {
            Modules::update($module);

            $message = ['level' => 'success', 'message' => trans('Settings::labels.message.module_update')];
        } catch (\Exception $exception) {
            $module->update(['notes' => $exception->getMessage()]);

            log_exception($exception, Module::class, 'install');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }
        return response()->json($message);
    }

    /**
     * @param ModuleRequest $request
     * @param Module $module
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function downloadRemote(ModuleRequest $request, Module $module)
    {
        try {
            Modules::downloadRemote($module);

            $message = ['level' => 'success', 'message' => trans('Settings::labels.message.module_download')];
        } catch (\Exception $exception) {
            $module->update(['notes' => $exception->getMessage()]);

            log_exception($exception, Module::class, 'downloadRemote');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param ModuleRequest $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function downloadNew(ModuleRequest $request)
    {
        $module_key = $request->get('module_key');
        $module_license_key = $request->get('license_key');


        try {
            if (!$module_key) {
                throw new \Exception(trans('Settings::exception.module.key'));
            }

            Modules::downloadNew($module_key, $module_license_key);


            flash(trans('Settings::labels.message.module_download'))->success();
            return redirectTo($this->resource_url);
        } catch (\Exception $exception) {
            log_exception($exception, Module::class, 'downloadnew');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];

            return response()->json($message, 422);
        }
    }


    /**
     * @param ModuleRequest $request
     * @param Module $module
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function uninstall(ModuleRequest $request, Module $module)
    {
        $message = $this->uninstallAction($module);

        return response()->json($message);
    }

    /**
     * @param Module $module
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function licenseKey(Module $module)
    {
        return view('Settings::modules.license_key')->with(compact('module'));
    }

    /**
     * @param Request $request
     * @param Module $module
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function saveLicenseKey(Request $request, Module $module)
    {
        $module->license_key = $request->get('license_key');
        $module->save();
        flash(trans('Settings::labels.message.license_update_success'))->success();
        return redirectTo($this->resource_url);
    }

    /**
     * @param $module
     * @param bool $log_exception
     * @return array
     */
    protected function uninstallAction($module, $log_exception = true)
    {
        try {
            Modules::uninstall($module);
            $message = ['level' => 'success', 'message' => trans('Settings::labels.message.module_uninstall')];
        } catch (\Exception $exception) {
            $module->update(['notes' => $exception->getMessage()]);

            if ($log_exception) {
                log_exception($exception, Module::class, 'install');
            }
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return $message;
    }

    /**
     * @param ModuleRequest $request
     * @param Module $module
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function toggleStatus(ModuleRequest $request, Module $module, $status)
    {
        try {
            $module->update(['notes' => ""]);

            $module_config = Modules::getModulesSettings($module->code);

            if (!$module_config) {
                throw new \Exception(trans('Settings::exception.settings.module_not_exist'));
            }

            if (!$module->installed) {
                throw new \Exception(trans('Settings::exception.module.code_not_install', ['name' => $module->code]));
            }
            if ($status == "disable") {
                Modules::checkModuleRequired($module);
            }
            $module->update([
                'enabled' => $status == 'enable',
            ]);

            $message = ['level' => 'success', 'message' => trans('Settings::labels.message.module_update')];
        } catch (\Exception $exception) {
            $module->update(['notes' => $exception->getMessage()]);

            log_exception($exception, Module::class, 'toggleStatus');

            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        Modules::clearModulesSettingsCache();

        return response()->json($message);
    }
}
