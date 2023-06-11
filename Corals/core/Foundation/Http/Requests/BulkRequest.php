<?php

namespace Corals\Foundation\Http\Requests;


class BulkRequest extends BaseRequest
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
        $rules = parent::rules();


        $rules = array_merge($rules, [
                'action' => 'required',
                'selection' => 'required',
            ]
        );


        return $rules;
    }


}
