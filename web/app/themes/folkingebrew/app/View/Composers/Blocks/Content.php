<?php 

namespace App\View\Composers\Blocks;

use Roots\Acorn\View\Composer;

class Content extends Composer
{
    public function with()
    {
        return [
            'backgroundColor' => get_field('background_color') ?? 'bg-white',
            'textRight' => get_field('text_right') ?? false,
            'text' => get_field('text') ?? '',
            'image' => get_field('image') ?? '',
            'title' => get_field('title') ?? '',
            'link' => get_field('link') ?? '',
        ];
    }

    // public function backgroundColor(): string
    // {
    //     return get_field('background_color') ?? 'bg-white';
    // }
}