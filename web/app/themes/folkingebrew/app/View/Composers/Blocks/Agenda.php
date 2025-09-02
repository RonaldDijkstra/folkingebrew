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
            'events' => get_field('events', 'options') ?: [],
        ];
    }
}
