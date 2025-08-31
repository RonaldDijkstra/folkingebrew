<?php 

namespace App\View\Composers\Blocks;

use Roots\Acorn\View\Composer;

class Content extends Composer
{
    protected static $views = [
        'blocks.content',
    ];

    public function with()
    {
        return [
            'backgroundColor' => get_field('background_color') ?? 'bg-white',
            'textRight' => get_field('text_right') ?? false,
            'text' => get_field('text') ?? '',
            'image' => get_field('image') ?? '',
            'title' => get_field('title') ?? '',
            'link' => get_field('link') ?? '',
            'contentType' => get_field('content_type') ?? 'text',
            'openingHours' => get_field('the_pub_opening_hours', 'options')['hours'] ?? [],
        ];
    }
}