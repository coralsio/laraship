<?php

namespace Corals\User\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\User\Models\Group;

class GroupRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Group::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Group::class);
        $rules = parent::rules();

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                    'name' => 'required|max:191|unique:groups,name',
                ]
            );
        }

        if ($this->isUpdate()) {
            $group = $this->route('group');

            $rules = array_merge($rules, [
                    'name' => 'required|max:191|unique:groups,name,' . $group->id,
                ]
            );
        }
        return $rules;
    }
}
