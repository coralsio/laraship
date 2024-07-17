<?php

namespace Corals\Utility\Hooks;

use Corals\Foundation\Facades\Actions;
use Corals\User\Models\User;
use Corals\Utility\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UtilityHook
{
    function pre_registration_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invitation_code' => [
                'sometimes',
                'required',
                Rule::exists('utility_invites', 'code')
                    ->when($request->get('email'), function ($query, $email) use ($request) {
                        $query->where('email', $email);
                    })
            ],
        ]);

        if ($validator->fails() || !Invitation::isValid($request->query('code'))) {
            throw new ValidationException($validator);
        }
    }


    function user_registered(User $user, Request $request)
    {
        if ($request->has('invitation_code')) {
            $invitation = Invitation::where('code', '=', $request->input('invitation_code'))->first();

            Actions::do_action('invitation_accepted', $user, $invitation);
        }
    }

    public function invitation_accepted(User $user, $invitation)
    {
        event('notifications.invitation.accepted', [
            'inviter' => $invitation->inviter,
            'invitee' => $user
        ]);

        $invitation->invalidateInvitationCode(true);

        flash(trans('Utility::messages.invite_friends.accepted'))->success();
    }

    public function auth_register_form()
    {
        $invitationCode = request()->invitation_code;

        echo $invitationCode ? '<input type="hidden" name="invitation_code" value="' . $invitationCode . '">' : '';
    }
}
