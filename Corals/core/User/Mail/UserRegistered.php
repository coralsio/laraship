<?php

namespace Corals\User\Mail;

use Corals\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegistered extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user, $body, $subject;

    /**
     * UserRegistered constructor.
     * @param User $user
     * @param null $subject
     * @param null $body
     */
    public function __construct(User $user, $subject = null, $body = null)
    {
        $this->user = $user;
        $this->body = $body;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view('User::mails.user_registered');
    }
}
