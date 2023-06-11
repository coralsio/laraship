<?php

namespace Corals\User\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Illuminate\Support\Str;

class UserImportRequest extends BaseRequest
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

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'file' => 'required|mimes:csv,txt|max:' . maxUploadFileSize(),
                'roles'=>'required'
            ]);
            $target = Str::singular(request()->segments()[1]);

        }

        return $rules;
    }
}
