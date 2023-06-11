<?php

namespace Corals\Utility\Http\Requests\InviteFriends;

use Corals\Foundation\Http\Requests\BaseRequest;

class InviteFriendsRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return user()->hasPermissionTo('Utility::invite_friends.can_send_invitation') || isSuperUser(user());
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
                'invitation_text' => 'required',
                'invitation_subject' => 'required',
                'friends' => 'required',
                'friends.*.email' => 'required|email',
                'friends.*.name' => 'required',
            ]);
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'friends.*.email' => trans('Utility::labels.invite_friends.email'),
            'friends.*.name' => trans('Utility::labels.invite_friends.name'),
        ];
    }
}
