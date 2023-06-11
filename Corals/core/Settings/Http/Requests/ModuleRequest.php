<?php

namespace Corals\Settings\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Settings\Models\Module;

class ModuleRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Module::class);

        return $this->can('manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Module::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, []);
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, []);
        }

        if ($this->isUpdate()) {
            $module = $this->route('module');
            $rules = array_merge($rules, []);
        }

        return $rules;
    }
}
