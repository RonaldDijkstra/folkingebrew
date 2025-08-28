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
            'backgroundImage' => $this->backgroundImage(),
            'title' => get_field('title') ?: '',
            'subtitle' => get_field('subtitle') ?: '',
            'agendaItems' => get_field('agenda_items') ?: [],
        ];
    }

    public function backgroundImage(): array
    {
        $image = get_field('background_image');
        
        return $image ?: [];
    }
}
