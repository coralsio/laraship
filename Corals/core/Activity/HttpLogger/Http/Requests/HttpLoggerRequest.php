<?php

namespace Corals\Activity\HttpLogger\Http\Requests;

use Corals\Activity\HttpLogger\Models\HttpLog;
use Corals\Foundation\Http\Requests\BaseRequest;

class HttpLoggerRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->model = $this->route('httpLog');

        $this->model = is_null($this->model) ? HttpLog::class : $this->model;

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        if ($this->isUpdate() || $this->isStore()) {
        }

        return $rules;
    }
}
