<?php

namespace App\View\Composers;

use Log1x\Navi\Facades\Navi;
use Roots\Acorn\View\Composer;


class FooterNavigation extends Composer
{
    protected static $views = [
        'sections.footer',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with(): array
    {
        return [
            'footerNavigation' => $this->footerNavigation(),
            'bottomNavigation' => $this->bottomNavigation(),
        ];
    }

    public function footerNavigation(): array
    {
        if (Navi::build('footer_navigation')->isEmpty()) return [];

        return Navi::build('footer_navigation')->toArray();
    }

    public function bottomNavigation(): array
    {
        if (Navi::build('bottom_navigation')->isEmpty()) return [];

        return Navi::build('bottom_navigation')->toArray();
    }
}
