<?php

namespace Corals\Settings\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Settings\Models\Setting;

class SettingRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Setting::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Setting::class);
        $rules = parent::rules();

        if ($this->isUpdate() && in_array('api',$this->route()->middleware())) {
            return [
                'value' => 'required',
            ];
        }

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'label' => 'required|max:191',
            ]);
        }

        if ($this->isStore()) {
            $types = join(',', array_keys(config('settings.types')));
            $categories = join(',', \Settings::getCategoriesList());
            $rules = array_merge($rules, [
                'code' => 'required|max:191|unique:settings',
                'type' => 'required|max:191|in:' . $types,
                'category' => 'required|max:191|in:' . $categories,
            ]);
        }

        if ($this->isUpdate()) {
            $setting = $this->route('setting');

            $rules = array_merge($rules, [
//                'code' => 'required|max:191|unique:settings,code,' . $setting->id,
//                'value' => 'required'
            ]);

            if ($setting->type == 'FILE') {
                $rules['value'] = 'mimes:' . config('settings.mimes');
            }
        }

        return $rules;
    }

    public function getValidatorInstance()
    {
        $data = $this->all();

        if ($this->isUpdate()) {

            $setting = $this->route('setting');

            switch ($setting->type) {
                case 'BOOLEAN':
                    $data['value'] = isset($data['value']) ? 'true' : 'false';
                    break;
                case 'SELECT':
                    if (in_array('api',$this->route()->middleware())) {
                        break;
                    }
                    
                    $value = [];

                    $items = $this->{$setting->code};

                    if (!empty($items) && is_array($items)) {
                        foreach ($items as $item) {
                            if (empty($item['key']) || empty($item['value'])) {
                                continue;
                            }
                            $value[$item['key']] = $item['value'];
                        }
                    }

                    if (empty($value)) {
                        $data['value'] = '';
                    } else {
                        $data['value'] = json_encode($value);
                    }

                    unset($data[$setting->code]);
                    break;
            }
            // prevent updating setting code
            unset($data['code']);

            $data['is_public'] = data_get($data, 'is_public', false) ?? false;

        }

        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}
