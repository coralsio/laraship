<?php

namespace Corals\User\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;

class UserAddressRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->route('user');

        return user()->can('update', $user) || $user->id === user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'address.address_1' => 'required',
                'address.type' => 'required|in:' . join(',', array_keys(\Settings::get('address_types'))),
                'address.city' => 'required',
                'address.state' => 'required',
                'address.zip' => 'required',
                'address.country' => 'required',
            ]);
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'address.address_1' => 'address 1',
            'address.type' => 'type',
            'address.city' => 'city',
            'address.state' => 'state',
            'address.zip' => 'zip',
            'address.country' => 'country',
        ];

        return $attributes;
    }
}
