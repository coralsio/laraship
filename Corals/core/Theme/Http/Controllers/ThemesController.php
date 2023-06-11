<?php

namespace Corals\Theme\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Theme\Http\Requests\ThemeRequest;


class ThemesController extends BaseController
{


    public function __construct()
    {
        $this->resource_url = config('themes.resource_url');
        $this->title = 'Theme::module.theme.title';
        $this->title_singular = 'Theme::module.theme.title_singular';

        parent::__construct();

    }

    /**
     * @param ThemeRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index(ThemeRequest $request)
    {
        $themes = collect(\Theme::all());

        $frontend_themes = $themes->where('type', 'frontend')->all();

        $admin_themes = $themes->where('type', 'admin')->all();

        $installed_themes = [];

        foreach ($admin_themes as $theme) {
            $installed_themes[] = ['version' => $theme->version, 'code' => $theme->name, 'license_key' => null];
        }

        foreach ($frontend_themes as $theme) {
            $installed_themes[] = ['version' => $theme->version, 'code' => $theme->name, 'license_key' => null];
        }

        try {
            $check_updates_result = \Modules::checkForUpdates($request->all(), $installed_themes, 'themes_remote_updates');
            $remote_updates = $check_updates_result['remote_updates'];

        } catch (\Exception $exception) {
            log_exception($exception, 'ThemesController', 'index');
        }

        return view('Theme::themes.index')->with(compact('frontend_themes', 'admin_themes', 'remote_updates'));
    }

    /**
     * @param ThemeRequest $request
     * @param $name
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function downloadUpdate(ThemeRequest $request, $name)
    {
        try {
            $tempPath = \Theme::theme_packages_path('tmp');

            \Theme::createTempFolder();

            \Modules::download($name, null, $tempPath);
            $filename = $name . '.zip';

            \Theme::installTheme($filename, true);

            // remove the downloaded module from the list
            $remote_updates = \Cache::get('themes_remote_updates', []);
            unset($remote_updates[$name]);
            \Cache::put('themes_remote_updates', $remote_updates, config('corals.cache_ttl'));

            $message = ['level' => 'success', 'message' => trans('Theme::labels.theme.theme_message_downloaded_update', ['name' => $name])];
        } catch (\Exception $exception) {
            log_exception($exception, 'ThemesController', 'downloadUpdate');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        } finally {
            // Rebuild Themes Cache
            \Theme::clearTempFolder();
            \Theme::rebuildCache();
        }

        return response()->json($message);
    }

    /**
     * @param ThemeRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addModal(ThemeRequest $request)
    {
        return view('Theme::themes.add');
    }

    public function addTheme(ThemeRequest $request)
    {
        try {
            \Actions::do_action('pre_install_theme', $request);

            $tempPath = \Theme::theme_packages_path('tmp');

            \Theme::createTempFolder();

            $key = 'theme';

            if ($request->hasFile($key)) {
                $filename = $request->file($key)->getClientOriginalName();

                $request->file($key)->move($tempPath, $filename);

                \Theme::installTheme($filename, $request->has('update_if_exist'));

                flash(trans('Theme::labels.theme.theme_message_added_successfully'))->success();
            } else {
                throw new \Exception(trans('Theme::exception.theme.theme_not_added'));
            }
        } catch (\Exception $exception) {
            log_exception($exception, 'ThemesController', 'addTheme');
        } finally {
            // Rebuild Themes Cache
            \Theme::clearTempFolder();
            \Theme::rebuildCache();
        }

        return redirectTo($this->resource_url);
    }


    public function uninstallTheme(ThemeRequest $request, $name)
    {
        try {
            \Actions::do_action('pre_uninstall_theme', $request, $name);

            // Check that theme exists
            if (!\Theme::exists($name)) {
                throw new \Exception(trans('Theme::exception.theme.theme_not_exist', ['name' => $name]));
            }

            // Get the theme
            $theme = \Theme::find($name);

            // Delete folders
            $theme->uninstall();

            $message = ['level' => 'success', 'message' => trans('Theme::labels.theme.theme_uninstalled_successfully', ['name' => $name])];
        } catch (\Exception $exception) {
            log_exception($exception, 'ThemesController', 'uninstallTheme');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    public function deactivateTheme(ThemeRequest $request, $type, $name)
    {
        try {
            \Actions::do_action('pre_deactivate_theme', $request, $type);

            // Check that theme exists
            if (!\Theme::exists($name) || !in_array($type, ['admin', 'frontend'])) {
                throw new \Exception(trans('Theme::exception.theme.theme_not_exist'));
            }

            \Settings::set("active_{$type}_theme", config('themes.corals_' . $type));

            $message = ['level' => 'success', 'message' => trans('Theme::labels.theme.theme_activated_successfully', ['name' => $name])];
        } catch (\Exception $exception) {
            log_exception($exception, 'ThemesController', 'activate');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    public function importDemo(ThemeRequest $request, $name)
    {
        try {
            \Actions::do_action('pre_import_theme_demo', $request);

            // Check that theme exists
            if (!\Theme::exists($name)) {
                throw new \Exception(trans('Theme::exception.theme.theme_not_exist'));
            }

            // Get the theme
            $theme = \Theme::find($name);

            $theme->importDemo();

            $message = ['level' => 'success', 'message' => trans('Theme::labels.theme.theme_imported_successfully', ['name' => $name])];
        } catch (\Exception $exception) {
            log_exception($exception, 'ThemesController', 'importDemo');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    public function activateTheme(ThemeRequest $request, $type, $name)
    {
        try {
            \Actions::do_action('pre_activate_theme', $request, $type, $name);

            // Check that theme exists
            if (!\Theme::exists($name) || !in_array($type, ['admin', 'frontend'])) {
                throw new \Exception(trans('Theme::exception.theme.theme_not_exist'));
            }

            \Settings::set("active_{$type}_theme", $name);

            $message = ['level' => 'success', 'message' => trans('Theme::labels.theme.theme_activated_successfully', ['name' => $name])];
        } catch (\Exception $exception) {
            log_exception($exception, 'ThemesController', 'activate');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }


}
