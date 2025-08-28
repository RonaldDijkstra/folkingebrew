<?php

namespace App\View\Composers\Blocks;

use Roots\Acorn\View\Composer;

class ReviewSlider extends Composer
{
    protected $view = 'blocks.review-slider';

    public function with(): array
    {
        return [
            'title' => get_field('title') ?: '',
            'subtitle' => get_field('subtitle') ?: '',
            'reviews' => get_field('reviews') ?: [],
        ];
    }
}
