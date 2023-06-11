<?php

namespace Corals\User\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\User\Models\Role;

class RoleRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Role::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Role::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                    'label' => 'required|max:191',
                ]
            );
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                    'name' => 'required|max:191|unique:roles,name',
                ]
            );
        }

        if ($this->isUpdate()) {
            $role = $this->route('role');

            $rules = array_merge($rules, [
                    'name' => 'required|max:191|unique:roles,name,' . $role->id,
                ]
            );
        }

        return $rules;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        $data = $this->all();

        $data['subscription_required'] = \Arr::get($data, 'subscription_required', false);
        $data['disable_login'] = \Arr::get($data, 'disable_login', false);

        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}
