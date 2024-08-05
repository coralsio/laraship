<?php

namespace Corals\Foundation\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class ExceptionsNotification extends Notification
{
    use Queueable;

    protected $exception;

    /**
     * @var bool
     */
    protected $includeTrace = true;

    /**
     * ExceptionsNotification constructor.
     * @param $exception
     * @param $includeTrace
     */
    public function __construct($exception, $includeTrace = true)
    {
        $this->exception = $exception;
        $this->includeTrace = $includeTrace;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toSlack($notifiable)
    {
        $slackMessage = (new SlackMessage())
            ->error()
            ->content(sprintf(':warning: %s [%s]',
                    $this->exception->getMessage(), config('app.name'))
            );

        if ($this->includeTrace) {
            $slackMessage->attachment(function ($attachment) {
                $attachment->title('Trace:')
                    ->content($this->exception->getTraceAsString());
            });
        }

        return $slackMessage;
    }
}
