<?php

namespace Corals\Utility\Http\Controllers\InviteFriends;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Utility\Http\Requests\InviteFriends\InviteFriendsRequest;
use Corals\Utility\Mail\InviteFriends\InvitationEmail;
use Corals\Foundation\Formatter\Formatter;

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

        $this->title = 'Utility::module.invite_friends.title';

        $this->title_singular = 'Utility::module.invite_friends.title';

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
        if (!empty($this->invitationTextSettingKey)) {
            $invitation_text = trans('Utility::labels.invite_friends.invitation_text_default_placeholder') . '&#13;&#10;';

            $invitation_text .= \Settings::get($this->invitationTextSettingKey);

            $this->setViewSharedData(['invitation_text' => $invitation_text]);
        }

        if (!empty($this->invitationSubjectSettingKey)) {
            $this->setViewSharedData(['invitation_subject' => \Settings::get($this->invitationSubjectSettingKey)]);
        }

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

            // to get unique emails
            $friends = $friends->pluck('name', 'email')->toArray();

            foreach ($friends as $email => $name) {
                $friendInvitationText = Formatter::format($invitationText, ['name' => $name]);

                $friendInvitationText = nl2br($friendInvitationText);

                \Mail::to($email)->queue(new InvitationEmail($friendInvitationText, $invitationSubject));
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
