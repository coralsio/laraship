<?php

namespace Corals\Settings\Http\Controllers\API;


use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Settings\Facades\Settings;
use Corals\Settings\Http\Requests\SettingRequest;
use Corals\Settings\Models\Setting;
use Corals\Settings\Services\SettingService;
use Corals\Settings\Transformers\API\SettingPresenter;
use Illuminate\Http\Request;

class SettingsController extends APIBaseController
{
    protected $settingService;

    /**
     * SettingsController constructor.
     * @param SettingService $settingService
     * @throws \Exception
     */
    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
        $this->settingService->setPresenter(new SettingPresenter());

        $this->corals_middleware_except = ['getActiveLanguages', 'getSettings', 'getSettingsByCategory'];

        parent::__construct();
    }

    public function update(SettingRequest $request, Setting $setting)
    {
        try {
            $setting = $this->settingService->update($request, $setting);

            return apiResponse($this->settingService->getModelDetails(),
                trans('Corals::messages.success.updated', ['item' => $setting->label]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    public function getSettingValue(Request $request, $code, $default = null)
    {
        try {
            if (!isSuperUser()) {
                abort(403, 'Forbidden!!');
            }

            $value = Settings::get($code, $default);

            if (is_array($value)) {
                $value = apiPluck($value);
            }

            return apiResponse($value);
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    public function getActiveLanguages()
    {
        try {
            return apiResponse(apiPluck(\Language::allowed(), 'code', 'label'));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function getSettings()
    {
        try {
            $settings = Setting::query()
                ->where('is_public', true)
                ->select('value', 'type', 'code')
                ->get()
                ->toArray();

            return apiResponse($settings);
        } catch (\Exception $e) {
            return apiExceptionResponse($e);
        }
    }
    /**
     * @param $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSettingsByCategory($category)
    {
        try {
            return apiResponse(Settings::getSettingsByCategory($category, true, false));
        } catch (\Exception $e) {
            return apiExceptionResponse($e);
        }
    }
}
