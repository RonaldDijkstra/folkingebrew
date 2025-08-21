<?php

namespace App\View\Composers;

use Log1x\Navi\Facades\Navi;
use Roots\Acorn\View\Composer;


class Navigation extends Composer
{
    protected static $views = [
        'sections.header',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with(): array
    {
        return [
            'primaryNavigation' => $this->primaryNavigation(),
        ];
    }

    public function primaryNavigation(): array
    {
        if (Navi::build('primary_navigation')->isEmpty()) return [];

        return Navi::build('primary_navigation')->toArray();
    }
}