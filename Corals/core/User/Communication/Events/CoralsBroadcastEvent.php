<?php

namespace Corals\User\Communication\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CoralsBroadcastEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var
     */
    protected $notification;

    /**
     * @var
     */
    protected $channelName;

    /**
     * @var
     */
    protected $notifiable;

    /**
     * CoralsBroadcastEvent constructor.
     * @param $channelName
     * @param $notification
     * @param $notifiable
     */
    public function __construct($channelName, $notification, $notifiable)
    {
        $this->channelName = $channelName;
        $this->notification = $notification;
        $this->notifiable = $notifiable;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('broadcasting.' . $this->channelName);
    }

    /**
     * @return mixed
     */
    public function broadcastAs()
    {
        $className = explode('.', $this->channelName);
        return 'broadcasting.' . $className[0];
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'unread_notifications_count' => $this->notifiable->unreadNotifications()->count(),
            'notification' => $this->notification->presenter()
        ];
    }
}
