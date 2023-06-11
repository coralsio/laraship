<?php

namespace Corals\User\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\User\Facades\TwoFactorAuth;

class ProfileRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'email' => 'required|max:191|unique:users,email,' . user()->id,
            'password' => 'nullable|confirmed|max:191|min:6',
            'picture' => 'nullable|mimes:jpg,jpeg,png|max:' . maxUploadFileSize(),

        ];

        $is_two_factor_auth_enabled = \TwoFactorAuth::isActive();

        if ($is_two_factor_auth_enabled) {
            $rules = array_merge($rules, TwoFactorAuth::profileValidation($this->all(), user()));
        }


        return $rules;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        $data = $this->all();

        $data['two_factor_auth_enabled'] = \Arr::get($data, 'two_factor_auth_enabled', false);
        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }

}
