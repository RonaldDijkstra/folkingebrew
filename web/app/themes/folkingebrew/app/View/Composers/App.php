<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class App extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*',
    ];

    public function with(): array
    {
        return [
            'showZenChefWidget' => $this->showZenChefWidget(),
            'siteName' => $this->siteName(),
        ];
    }

    /**
     * Retrieve the site name.
     */
    public function siteName(): string
    {
        return get_bloginfo('name', 'display');
    }

    /**
     * Retrieve whether the ZenChef widget should be shown.
     */
    public function showZenChefWidget(): bool
    {
        $showZenChefWidget = false;

        if(get_field('show_zenchef_widget', get_the_ID())) {
            $showZenChefWidget = true;
        }

        return $showZenChefWidget;
    }
}
