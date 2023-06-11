<?php

namespace Corals\User\Communication\Classes;


use Carbon\Carbon;
use Corals\User\Communication\Models\NotificationTemplate;
use Corals\User\Models\User;

class CoralsNotification
{
    public $events = [];

    /**
     * Notification constructor.
     */
    function __construct()
    {
    }

    /**
     * @param $name
     * @param $event_name
     * @param $friendlyName
     * @param $notificationClass
     * @param string $title
     * @param array $body
     */
    public function addEvent($event_name, $friendlyName, $notificationClass, $name = '', $title = '', $body = [])
    {
        if (empty($name)) {
            $name = $event_name;
        }

        $this->events[$name] = [
            'name' => $name,
            'notificationClass' => $notificationClass,
            'friendly_name' => $friendlyName,
            'event_name' => $event_name,
            'title' => $title,
            'body' => json_encode($body)
        ];
    }

    public function getEventsList()
    {
        return $this->events;
    }

    public function getEventByEventName($event_name)
    {
        $events = collect($this->getEventsList());

        return $events->where('event_name', $event_name);
    }

    public function getEventByName($name)
    {
        return $this->getEventsList()[$name];
    }

    public function insertNewEventsToDatabase()
    {
        $eventsInDatabase = NotificationTemplate::query()->get();

        $eventsNamesInDatabase = $eventsInDatabase->pluck('name')->toArray();

        $allEventsNames = array_keys($this->getEventsList());

        $newEventsNames = array_diff($allEventsNames, $eventsNamesInDatabase);

        $newEventsNames = array_map(function ($name) {
            $event = $this->getEventByName($name);

            $eventObj = [
                'name' => $name,
                'friendly_name' => $event['friendly_name'],
                'title' => $event['title'],
                'body' => $event['body'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            if (\Schema::hasColumn('notification_templates', 'event_name')) {
                $eventObj['event_name'] = $event['event_name'];
            }

            return $eventObj;
        }, $newEventsNames);

        if (!empty($newEventsNames)) {
            NotificationTemplate::query()->insert($newEventsNames);
        }
    }

    /*
     * @return array
     * function to return the notification parameters and there description for a given template
     */
    public function getNotificationParametersDescription(NotificationTemplate $notificationTemplate)
    {
        return $this->getEventByName($notificationTemplate->name)['notificationClass']::getNotificationMessageParametersDescriptions();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getUserNotificationTemplates(User $user)
    {
        return NotificationTemplate::whereHas('roles', function ($query) use ($user) {
            $query->whereIn('role_id', $user->roles()->pluck('id'));
        })->where('via', 'like', '%user_preferences%')->get();
    }
}
