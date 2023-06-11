<?php

use Corals\User\Communication\Facades\CoralsNotification;
use Corals\User\Communication\Models\NotificationTemplate;

$templatesInDatabase = NotificationTemplate::query()->get();

$reFetchTemplates = false;

foreach ($templatesInDatabase as $template) {
    if (empty($template->body) && empty($template->title)) {
        $template->delete();
        $reFetchTemplates = true;
    }
}

if ($reFetchTemplates) {
    $templatesInDatabase = NotificationTemplate::query()->get();
}

$eventsNamesInDatabase = $templatesInDatabase->pluck('name')->toArray();

$allEventsNames = array_keys(CoralsNotification::getEventsList());

$newEventsNames = array_diff($allEventsNames, $eventsNamesInDatabase);

$newEventsNames = array_map(function ($name) {
    $event = CoralsNotification::getEventByName($name);

    $eventObj = [
        'name' => $name,
        'friendly_name' => $event['friendly_name'],
        'title' => $event['title'],
        'body' => $event['body'],
        'created_at' => now(),
        'updated_at' => now()
    ];

    if (\Schema::hasColumn('notification_templates', 'event_name')) {
        $eventObj['event_name'] = $event['event_name'];
    }

    return $eventObj;
}, $newEventsNames);

if (!empty($newEventsNames)) {
    foreach ($newEventsNames as $eventObj) {
        $templatesWithSameEventNames = $templatesInDatabase->where('event_name', $eventObj['event_name']);

        if ($templatesWithSameEventNames->count() == 1) {
            NotificationTemplate::query()->where('event_name', $eventObj['event_name'])->update(\Illuminate\Support\Arr::only($eventObj, ['name']));
        } elseif ($templatesWithSameEventNames->count() > 1) {
            //should be solved manually
        } else {
            NotificationTemplate::query()->insert($eventObj);
        }
    }
}