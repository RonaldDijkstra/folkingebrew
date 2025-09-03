<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class SingleBeer extends Composer
{
    protected static $views = [
        'single-beers',
    ];

    public function with()
    {
        return [
            'title' => get_the_title(),
            'style' => get_field('style'),
            'abv' => get_field('abv'),
            'image' => get_field('image'),
            'wallpaper' => get_field('wallpaper'),
            'description' => get_field('description'),
            'specs' => get_field('specs'),
            'untappd_link' => get_field('untappd_link'),
            'archiveLink' => $this->getBeersArchiveLink(),
        ];
    }

    protected function getBeersArchiveLink()
    {
        return get_post_type_archive_link('beers');
    }
}