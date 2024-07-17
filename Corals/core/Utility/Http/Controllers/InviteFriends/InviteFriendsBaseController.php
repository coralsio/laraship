<?php

namespace Corals\Utility\Http\Controllers\InviteFriends;

use Corals\Foundation\Facades\Filters;
use Corals\Foundation\Formatter\Formatter;
use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Settings\Facades\Settings;
use Corals\Utility\Http\Requests\InviteFriends\InviteFriendsRequest;
use Corals\Utility\Models\Invitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InviteFriendsBaseController extends BaseController
{
    protected $successMessage = 'Utility::messages.invite_friends.success.invitation_sent';
    protected $redirectUrl = 'dashboard';
    protected $inviteFormView = 'Utility::invite_friends.create';
    protected $invitationTextSettingKey = null;
    protected $invitationSubjectSettingKey = null;
    protected $invitationResourceURL = null;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->setCommonVariables();
            $this->resource_url = $this->invitationResourceURL;

            return $next($request);
        });

        $this->title = trans('Utility::module.invitation.title', ['title' => 'Invite Friends']);

        $this->title_singular = trans('Utility::module.invitation.title', ['title' => 'Invite Friends']);

        parent::__construct();
    }

    protected function setCommonVariables()
    {
        $this->invitationResourceURL = config('utility.models.invite_friends.resource_url');
    }

    /**
     * @param InviteFriendsRequest $request
     * @return mixed
     */
    public function getInviteFriendsForm(InviteFriendsRequest $request)
    {
        $invitation_text = trans('Utility::labels.invite_friends.invitation_text_default_placeholder') . '&#13;&#10;';
        $invitation_subject = '';

        if (!empty($this->invitationSubjectSettingKey)) {
            $invitation_subject = Settings::get($this->invitationSubjectSettingKey);
        }

        if (!empty($this->invitationTextSettingKey)) {
            $invitation_text .= Settings::get($this->invitationTextSettingKey);
        }

        $this->setViewSharedData(Filters::do_filter('invite_form_defaults', [
            'invitation_subject' => $invitation_subject,
            'invitation_text' => $invitation_text,
        ]));

        return view($this->inviteFormView);
    }

    /**
     * @param InviteFriendsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendInvitation(InviteFriendsRequest $request)
    {
        try {
            $invitationText = $request->get('invitation_text');

            $invitationSubject = $request->get('invitation_subject');

            $friends = collect($request->get('friends'));

            $friends = $friends->pluck('name', 'email')->toArray();

            foreach ($friends as $email => $name) {
                $code = Str::random(10);

                $eventData = Filters::do_filter('send_invitation_data', [
                    'email' => $email,
                    'name' => $name,
                    'emailSubject' => $invitationSubject,
                    'emailBody' => $invitationText,
                    'code' => $code,
                    'url' => sprintf("%s?invitation_code=%s&email=%s", route('register'), $code, $email)
                ]);

                $friendInvitationText = Formatter::format(data_get($eventData, 'emailBody'), [
                    'name' => $name,
                    'accept_link' => trans('Utility::labels.notifications.invitation.link', ['url' => data_get($eventData, 'url')])
                ]);

                $friendInvitationText = nl2br($friendInvitationText);

                Invitation::create([
                    'email' => data_get($eventData, 'email'),
                    'message' => $friendInvitationText,
                    'subject' => $invitationSubject,
                    'inviter_id' => Auth::id(),
                    'code' => $code,
                    'properties' => [
                        'text' => $invitationText
                    ]
                ]);

                $eventData['emailBody'] = $friendInvitationText;

                event('notifications.user.invitation', $eventData);
            }

            $message = ['level' => 'success', 'message' => trans($this->successMessage)];
        } catch (\Exception $exception) {
            log_exception($exception, get_class($this), 'sendInvitation');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        if (is_null($this->redirectUrl)) {
            return response()->json($message);
        } else {
            if ($message['level'] === 'success') {
                flash($message['message'])->success();
            } else {
                flash($message['message'])->error();
            }
            return redirectTo($this->redirectUrl);
        }
    }
}
