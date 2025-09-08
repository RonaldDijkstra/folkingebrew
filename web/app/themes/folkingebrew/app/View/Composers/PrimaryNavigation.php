<?php

namespace App\View\Composers;

use Log1x\Navi\Facades\Navi;
use Roots\Acorn\View\Composer;


class PrimaryNavigation extends Composer
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

        $navigation = Navi::build('primary_navigation')->toArray();

        // Make "Beers" parent menu item active when on single beer page
        if (is_singular('beers')) {
            $navigation = $this->markBeersParentActive($navigation);
        }

        return $navigation;
    }

    /**
     * Mark the Beers archive menu item as active when on a single beer page.
     *
     * @param array $navigation
     * @return array
     */
    protected function markBeersParentActive(array $navigation): array
    {
        $beersArchiveUrl = get_post_type_archive_link('beers');

        foreach ($navigation as &$item) {
            // Check if this menu item links to the beers archive
            if ($item->url === $beersArchiveUrl) {
                $item->active = true;
                break;
            }
        }

        return $navigation;
    }
}
