<?php

namespace Corals\User\Communication\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\User\Communication\Models\Notification;

class NotificationRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->model = $this->route('notification');

        $this->model = is_null($this->model) ? Notification::class : $this->model;

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
            $rules = array_merge($rules, [
            ]);
        }

        if ($this->isStore()) {

            $rules = array_merge($rules, [
            ]);
        }

        if ($this->isUpdate()) {
            $notification = $this->route('notification');

            $rules = array_merge($rules, [
            ]);

        }

        return $rules;
    }

}
