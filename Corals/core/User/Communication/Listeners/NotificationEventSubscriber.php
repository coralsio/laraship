<?php

namespace Corals\User\Communication\Listeners;


use Corals\User\Communication\Facades\CoralsNotification;
use Corals\User\Communication\Models\NotificationHistory;
use Corals\User\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class NotificationEventSubscriber
{

    public function handleNotificationEvent($eventName, $data)
    {
        $events = CoralsNotification::getEventByEventName($eventName);

        foreach ($events as $event) {
            $notificationClass = app($event['notificationClass']);

            $notificationClass->initNotification($event['name'], $event['event_name'], $data);

            $notificationTemplate = $notificationClass->getNotificationTemplate();

            if (!$notificationClass->isTemplateActive()) {
                logger("Notification Template [ {$event['event_name']} ] is inactive");
                continue;
            }

            $notifiables = $notificationClass->getNotifiables();

            $notifiables = ($notifiables instanceof Collection) ? $notifiables : (is_array($notifiables) ? collect($notifiables) : collect([$notifiables]));

            // check if notificationTemplate has bcc roles
            $bcc_roles = ($notificationTemplate->extras['bcc_roles'] ?? null);

            if (!empty($bcc_roles)) {
                $bcc_roles_users = User::query()->whereHas('roles', function ($query) use ($bcc_roles) {
                    $query->whereIn('id', $bcc_roles);
                })->select('users.*')->get();

                $notifiables = $notifiables->merge($bcc_roles_users);
            }

            // check if notificationTemplate has bcc users
            $bcc_users_ids = ($notificationTemplate->extras['bcc_users'] ?? null);

            if (!empty($bcc_users_ids)) {
                $bcc_users = User::query()->whereIn('id', $bcc_users_ids)->select('users.*')->get();
                $notifiables = $notifiables->merge($bcc_users);
            }

            // get unique notifiables after merge
            $notifiables = $notifiables->unique(function ($item) {
                return class_basename($item) . $item['id'];
            });

            Notification::send($notifiables, $notificationClass);

            // check if notificationTemplate has to channels Custom
            $channelsCustom = ($notificationTemplate->extras['custom'] ?? null);
            $this->sendOnDemandNotifiables($channelsCustom, $notificationClass);

            $onDemandNotifiables = $notificationClass->getOnDemandNotificationNotifiables();
            $this->sendOnDemandNotifiables($onDemandNotifiables, $notificationClass);

            $this->logNotification($notificationClass, $notificationTemplate, $notifiables, $onDemandNotifiables);
        }
    }

    protected function sendOnDemandNotifiables($onDemandNotifiables, $notificationClass)
    {
        if (!empty($onDemandNotifiables) && is_array($onDemandNotifiables)) {
            foreach ($onDemandNotifiables as $channel => $values) {
                if (is_array($values)) {
                    foreach ($values as $value) {
                        Notification::route($channel, $value)
                            ->notify($notificationClass);
                    }
                } else {
                    Notification::route($channel, $values)
                        ->notify($notificationClass);
                }
            }
        }
    }

    /**
     * @param $notificationClass
     * @param $notificationTemplate
     * @param $notifiables
     * @param $onDemandNotifiables
     */
    protected function logNotification($notificationClass, $notificationTemplate, $notifiables, $onDemandNotifiables)
    {
        $notifiablesRoutes = [];
        $body = [];

        if (method_exists($notificationClass, 'getModel')) {
            $modelObject = $notificationClass->getModel();
        }

        foreach ($notificationTemplate->via as $channel) {
            $body[$channel] = $notificationClass->getMessageBodyByChannel($channel);

            foreach ($notifiables as $notifiable) {
                $notifiablesRoutes[$channel][] = $notifiable->routeNotificationFor($channel);
            }

            if (isset($onDemandNotifiables[$channel])) {
                $notifiablesRoutes[$channel] = array_merge($notifiablesRoutes[$channel] ?? [], Arr::wrap($onDemandNotifiables[$channel]));
            }
        }

        NotificationHistory::query()->create([
            'model_id' => isset($modelObject) ? $modelObject->id : null,
            'model_type' => isset($modelObject) ? getMorphAlias($modelObject) : null,
            'notification_name' => $notificationClass->getTemplateName(),
            'channels' => $notificationTemplate->via,
            'body' => $body,
            'notifiables' => $notifiablesRoutes
        ]);
    }

    /**
     * @param $events
     */
    public function subscribe($events)
    {
        //listen for every event in the system
        $events->listen('notifications.*',
            'Corals\User\Communication\Listeners\NotificationEventSubscriber@handleNotificationEvent'
        );
    }
}
