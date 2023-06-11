<?php

namespace Corals\Theme\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;

class ThemeRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return user()->can('Settings::theme.manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        if ($this->is('themes/add') && $this->isMethod('POST')) {
            $rules['theme'] = 'required|mimes:zip';
        }

        return $rules;
    }
}
