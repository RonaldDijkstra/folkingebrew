<?php

namespace App\View\Composers\Blocks;

use Roots\Acorn\View\Composer;

class Agenda extends Composer
{

    protected static $views = [
        'blocks.agenda',
    ];

    public function with()
    {
        return [
            'backgroundImage' => get_field('background_image') ?: [],
            'title' => get_field('title') ?: '',
            'subtitle' => get_field('subtitle') ?: '',
            'events' => $this->getEvents(),
        ];
    }

    public function getEvents()
    {
        $events = get_field('events', 'options') ?: [];

        $today = strtotime(date('Y-m-d'));
        $filteredEvents = [];

        foreach ($events as $event) {
            $eventDate = $event['date'] ?? null;

            if (!$eventDate) {
                continue;
            }

            // Parse the date - assuming format is dd-mm or dd-mm-YYYY
            $dateParts = explode('-', $eventDate);
            if (count($dateParts) === 2) {
                // If only dd-mm, add current year
                $eventDate = $dateParts[0] . '-' . $dateParts[1] . '-' . date('Y');
            }

            // Convert to Y-m-d format for comparison
            $eventDateParts = explode('-', $eventDate);
            if (count($eventDateParts) === 3) {
                $eventDateFormatted = $eventDateParts[2] . '-' . $eventDateParts[1] . '-' . $eventDateParts[0];
                $eventTimestamp = strtotime($eventDateFormatted);

                if ($eventTimestamp && $eventTimestamp >= $today) {
                    $filteredEvents[] = $event;
                }
            }
        }

        $events = $filteredEvents;

        return $events;
    }
}
