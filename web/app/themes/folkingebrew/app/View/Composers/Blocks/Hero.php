<?php

namespace App\View\Composers\Blocks;

use Roots\Acorn\View\Composer;

class Hero extends Composer
{
    public function with(): array
    {
        return [
            'title' => $this->title(),
        ];
    }
    
    public function title(): string
    {
        return get_field('title') ?: '';
    }
}
