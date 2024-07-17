<?php

namespace Corals\Utility\Mail\InviteFriends;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invitationText;

    public $invitationSubject;

    /**
     * InvitationEmail constructor.
     *
     * @param $invitationText
     */
    public function __construct($invitationText, $invitationSubject)
    {
        $this->invitationText = $invitationText;
        $this->invitationSubject = $invitationSubject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject($this->invitationSubject)
            ->view('Notification::mail.general_email_template',
                ['body' => $this->invitationText]);
    }
}
