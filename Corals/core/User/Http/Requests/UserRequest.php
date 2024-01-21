<?php

namespace Corals\User\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\User\Facades\Roles;
use Corals\User\Facades\TwoFactorAuth;
use Corals\User\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->model = $this->route('user');

        $this->model = is_null($this->model) ? User::class : $this->model;

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(User::class);
        $rules = parent::rules();
        $is_two_factor_auth_enabled = \TwoFactorAuth::isActive();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                    'name' => 'required|max:191',
                    'last_name' => 'required|max:191',
                    'roles' => 'required',
                    'roles.*' => Rule::in(array_keys(Roles::getRolesList()->toArray())),
                    'status' => 'required',
                    'picture' => 'mimes:jpg,jpeg,png|max:' . maxUploadFileSize(),
                ]
            );
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                    'email' => 'required|email|max:191|unique:users,email',
                    'password' => 'required|confirmed|max:191|min:6'
                ]
            );
        }

        if ($this->isUpdate()) {
            $user = $this->route('user');

            $rules = array_merge($rules, [
                    'email' => 'required|email|max:191|unique:users,email,' . $user->id,
                    'password' => 'nullable|confirmed|max:191|min:6'
                ]
            );
            if ($is_two_factor_auth_enabled) {
                $rules = array_merge($rules, TwoFactorAuth::registrationValidation($this->all()));
            }
        }
        return $rules;
    }

    protected function getValidatorInstance()
    {
        if ($this->isStore()) {
            $data = $this->all();
            if (empty($data['password'])) {
                $data['password'] = Str::random(6);
                $data['password_confirmation'] = $data['password'];

                $this->getInputSource()->replace($data);
            }
        }

        return parent::getValidatorInstance();
    }
}
