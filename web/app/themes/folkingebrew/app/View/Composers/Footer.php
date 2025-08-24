<?php

namespace App\View\Composers;

use Log1x\Navi\Facades\Navi;
use Roots\Acorn\View\Composer;


class Footer extends Composer
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
            'footerText' => $this->footerText(),
            'footerNavigation' => $this->footerNavigation(),
            'bottomNavigation' => $this->bottomNavigation(),
        ];
    }

    public function footerText(): string
    {
        return get_field('footer_text', 'option');
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
