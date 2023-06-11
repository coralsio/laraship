<?php

namespace Corals\Settings\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Settings\Models\CustomFieldSetting;
use Illuminate\Support\Str;

class CustomFieldSettingRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(CustomFieldSetting::class, 'customFieldSetting');

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(CustomFieldSetting::class, 'customFieldSetting');
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'fields.*.type' => 'required|max:191',
                'fields.*.name' => 'required|max:191',
                'fields.*.status' => 'required',
                'fields.*.options_setting.source' => 'required_if:type,select'
            ]);
            if (in_array($this->get('type'), ['select', 'radio', 'multi_values'])) {
                if ($this->get('options_setting')['source'] == "static") {
                    foreach ($this->get('options', []) as $id => $item) {
                        $rules = array_merge($rules, [
                            "options.{$id}.key" => 'required',
                            "options.{$id}.value" => 'required',
                        ]);
                    }
                } elseif ($this->get('options_setting')['source'] == "database") {
                    $rules = array_merge($rules, [
                        "options_setting.source_model" => 'required',
                        "options_setting.source_model_column" => 'required',
                    ]);
                }
            }


            foreach ($this->get('custom_attributes', []) as $id => $item) {
                $rules = array_merge($rules, [
                    "custom_attributes.{$id}.key" => 'required',
                    "custom_attributes.{$id}.value" => 'required',
                ]);
            }
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
//                'fields.*.name' => 'required|max:191|unique:custom_field_settings,name,null,id,model,' . $this->get('model')
                'model' => 'required|max:191|unique:custom_field_settings,model',

            ]);
        }

        if ($this->isUpdate()) {
            $customFieldSetting = $this->route('customFieldSetting');
            $rules = array_merge($rules, [
//                'fields.*.name' => "required|max:191|unique:custom_field_settings,name,{$customFieldSetting->id},id,model,{$this->get('model')}",
                'model' => 'required|max:191|unique:custom_field_settings,model,' . $customFieldSetting->id,

            ]);
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        foreach ($this->get('options', []) as $id => $item) {
            $attributes["fields.*.options.{$id}.key"] = 'Key';
            $attributes["fields.*.options.{$id}.value"] = 'Value';
        }

        foreach ($this->get('custom_attributes', []) as $id => $item) {
            $attributes["fields.*.custom_attributes.{$id}.key"] = 'Key';
            $attributes["fields.*.custom_attributes.{$id}.value"] = 'Value';
        }

        $attributes = array_merge($attributes, [
            'fields.*.type' => 'Type',
            'fields.*.name' => 'Name',
            'fields.*.status' => 'Status',
            'fields.*.options_setting.source' => 'Source'
        ]);

        return $attributes;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        if ($this->isStore() || $this->isUpdate()) {
            $data = $this->all();

            foreach ($data['fields'] ?? [] as $index => $field) {
                if (isset($field['name'])) {
                    $data['fields'][$index]['name'] = Str::slug($field['name'], '_');
                }
            }

            $this->getInputSource()->replace($data);
        }


        return parent::getValidatorInstance();
    }
}
