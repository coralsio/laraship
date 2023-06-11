<?php

namespace Corals\User\Communication\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\User\Communication\Models\NotificationTemplate;

class NotificationTemplateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->model = $this->route('notification_template');

        $this->model = is_null($this->model) ? NotificationTemplate::class : $this->model;

        if ($this->isCreate() or $this->isStore() or $this->isDelete()) {
            return false;
        } else {
            return $this->isAuthorized();
        }
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
            $notification_template = $this->route('notification_template');

            $rules = array_merge($rules, [
                'title' => 'required',
                'via' => 'required',
                'extras.custom.mail.*' => 'email',
            ]);

            foreach ($this->get('via', []) as $via) {
                $rules = array_merge($rules, [
                    "body.{$via}" => 'required',
                ]);
            }
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        foreach ($this->input('extras.custom.mail', []) as $index => $email) {
            $attributes['extras.custom.mail.' . $index] = $email;
        }

        return $attributes;
    }

    public function messages()
    {
        return [
            'body.*' => trans('Notification::validation.messages.notification_template.body'),
        ];
    }

}
