<?php

namespace App\Services;

use App\Models\TimelineEvent;
use Illuminate\Database\Eloquent\Model;

class TimelineEventService
{
    /**
     * Create a new timeline event.
     */
    public function createEvent(int $userId, string $type, Model $eventable, ?int $regionId = null, array $payload = []): TimelineEvent
    {
        return TimelineEvent::create([
            'user_id' => $userId,
            'type' => $type,
            'eventable_type' => get_class($eventable),
            'eventable_id' => $eventable->getKey(),
            'region_id' => $regionId,
            'payload' => $payload,
        ]);
    }

    /**
     * Delete an event by its eventable model.
     */
    public function deleteEventFor(Model $eventable): void
    {
        TimelineEvent::where('eventable_type', get_class($eventable))
            ->where('eventable_id', $eventable->getKey())
            ->delete();
    }
}
