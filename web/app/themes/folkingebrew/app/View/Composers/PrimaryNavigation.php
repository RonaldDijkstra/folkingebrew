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

        // Make "Webshop" menu item active when on WooCommerce pages
        if (function_exists('is_woocommerce') && is_woocommerce()) {
            $navigation = $this->markWebshopActive($navigation);
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
                $item->activeParent = true;
                break;
            }

            // Check children
            if (!empty($item->children)) {
                foreach ($item->children as &$child) {
                    if ($child->url === $beersArchiveUrl) {
                        $child->active = true;
                        $item->activeParent = true;
                        $item->activeAncestor = true;
                        break 2;
                    }
                }
            }
        }

        return $navigation;
    }

    /**
     * Mark the Webshop menu item as active when on WooCommerce pages.
     *
     * @param array $navigation
     * @return array
     */
    protected function markWebshopActive(array $navigation): array
    {
        $shopUrl = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '';

        foreach ($navigation as &$item) {
            // Check if this menu item links to the shop page
            if ($item->url === $shopUrl) {
                $item->active = true;
                $item->activeParent = true;
                break;
            }

            // Check children
            if (!empty($item->children)) {
                foreach ($item->children as &$child) {
                    if ($child->url === $shopUrl) {
                        $child->active = true;
                        $item->activeParent = true;
                        $item->activeAncestor = true;
                        break 2;
                    }
                }
            }
        }

        return $navigation;
    }
}
