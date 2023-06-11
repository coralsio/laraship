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
     * SlackNotification constructor.
     * @param $exception
     */
    public function __construct($exception)
    {
        $this->exception = $exception;
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
        return (new SlackMessage())
            ->error()
            ->content(sprintf(':warning: %s [%s]',
                $this->exception->getMessage(), config('app.name')))
            ->attachment(function ($attachment) {
                $attachment->title('Trace:')
                    ->content($this->exception->getTraceAsString());
            });
    }
}
