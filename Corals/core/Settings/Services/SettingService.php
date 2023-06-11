<?php

namespace Corals\Settings\Services;

use Corals\Foundation\Services\BaseServiceClass;
use Corals\Foundation\Traits\FileUploadTrait;

class SettingService extends BaseServiceClass
{
    use FileUploadTrait;

    /**
     * @param $request
     * @param $modelClass
     * @param array $additionalData
     * @return mixed
     */
    public function store($request, $modelClass, $additionalData = [])
    {
        $data = array_merge($this->getRequestData($request), $additionalData);

        $setting = $modelClass::query()->create($data);

        $this->model = $setting;

        return $setting;
    }

    /**
     * @param $request
     * @param $setting
     * @param array $additionalData
     * @return mixed
     */
    public function update($request, $setting, $additionalData = [])
    {
        if ($setting->type == 'FILE') {
            $this->deleteFile($setting->getFilePath());
            $request = $this->saveFiles($request, config('settings.upload_path'));
        }

        $data = array_merge($this->getRequestData($request), $additionalData);

        $setting->update($data);

        $this->model = $setting;

        return $setting;
    }

    /**
     * @param $request
     * @param $setting
     */
    public function destroy($request, $setting)
    {
        if ($setting->type == 'FILE') {
            @unlink($setting->value);
        }

        $setting->delete();
    }
}