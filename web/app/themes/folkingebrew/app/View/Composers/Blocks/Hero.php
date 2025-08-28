<?php

namespace App\View\Composers\Blocks;

use Roots\Acorn\View\Composer;

class Hero extends Composer
{
    protected static $views = [
        'blocks.hero',
    ];

    public function with(): array
    {
        return [
            'backgroundType' => $this->backgroundType(),
            'backgroundImage' => $this->backgroundImage(),
            'title' => $this->title(),
            'image' => $this->image(),
            'buttons' => $this->buttons(),
        ];
    }
    
    public function title(): string
    {
        return get_field('title') ?: '';
    }

    public function image(): array
    {
        $image = get_field('image');
        
        return $image ?: [];
    }

    public function backgroundType(): string
    {
        return get_field('background_type') ?: 'primary';
    }

    public function backgroundImage(): array
    {
        return get_field('background_image') ?: [];
    }

    public function buttons(): array
    {
        return get_field('buttons') ?: [];
    }
}
