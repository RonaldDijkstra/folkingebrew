<?php

namespace Custom\Setup\Admin;

use Custom\Setup\ServiceInterface;
use Custom\Setup\Helpers\FileLocator;

class AdminBar implements ServiceInterface
{
    public function register()
    {
        add_action('admin_bar_menu', [$this, 'removeDefaultMenuItemsFromAdminBar'], 100);
        add_action('admin_bar_menu', [$this, 'removeWPIcon'], 999);
        add_action('admin_bar_menu', [$this, 'addFolkingebrewIcon'], 2);
        add_action('admin_head', [$this, 'adminBarStyling']);
        add_action('wp_head', [$this, 'adminBarStyling']);
    }

    /**
     * Remove default menu items from the admin top bar
     *
     * @return void
     */
    public function removeDefaultMenuItemsFromAdminBar(): void
    {
        global $wp_admin_bar;

        $unwantedMenuItems = [
            'customize',
            'comments',
            'new-content',
        ];

        foreach ($unwantedMenuItems as $item) {
            $wp_admin_bar->remove_menu($item);
        }
    }

    /**
     * Remove the WordPress icon from the admin top bar
     *
     * @return void
     */
    public function removeWPIcon(): void
    {
        global $wp_admin_bar;

        $wp_admin_bar->remove_node('wp-logo');
    }

    /**
     * Add the Folkingebrew icon to the admin top bar
     *
     * @return void
     */
    public function addFolkingebrewIcon(): void
    {
        global $wp_admin_bar;

        $iconUrl = FileLocator::getViteAssetUrl('resources/images/icon-folkingebrew.svg');

        $wp_admin_bar->add_node([
            'id' => 'folkingebrew-icon',
            'title' => '<img src="' . $iconUrl . '" alt="Folkingebrew" width="32" height="32" />',
            'href' => 'https://folkingebrew.nl',
            'meta' => ['target' => '_blank', 'class' => 'folkingebrew-icon'],
        ]);
    }

    /**
     * Add styling for the admin bar icon
     *
     * @return void
     */
    public function adminBarStyling(): void
    {
        echo '<style>
            #wpadminbar .ab-top-menu .folkingebrew-icon .ab-item {
                padding: 0;
                width: 32px;
            }
        </style>';
    }
}
