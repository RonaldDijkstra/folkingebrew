<?php

namespace Custom\Setup\Admin;

use Custom\Setup\ServiceInterface;

class Menu implements ServiceInterface
{
    public function register() {
        add_action('admin_menu', [$this, 'disableCommentsAdminMenu']);
        add_action('admin_menu', [$this, 'removeAppearanceMenuItem']);
        add_action('admin_menu', [$this, 'addMenuSeparators']);
        add_action('admin_menu', [$this, 'rearrangeAdminMenu']);
        add_action('admin_head', [$this, 'menuStyling']);
        add_action('admin_menu', [$this, 'removeMediaMenuItem']);
    }

    public function disableCommentsAdminMenu() {
        remove_menu_page('edit-comments.php');
    }

    public function removeAppearanceMenuItem() {
        remove_menu_page('themes.php');
    }

    /**
     * Here we add some separators between sections in the WP navigation
     * for more clarity
     *
     * @return void
     */
    public function addMenuSeparators(): void
    {
        global $menu;

        // Unset default separators
        $defaultSeparators = [3, 59, 99];

        foreach ($defaultSeparators as $separator) {
            unset($menu[$separator]);
        }

        // Define custom positions and their headers
        $customSeparators = [
            4  => 'Content',
            50 => 'Webshop',
            60 => 'Settings',
        ];

        // Add custom menu items or separators
        foreach ($customSeparators as $position => $title) {
            $menu[$position] = [
                0 => $title,
                1 => 'read',
                2 => $title ? '#' . strtolower($title) : 'separator' . $position,
                3 => '',
                4 => 'menu-separator'
            ];
        }
    }

    /**
     * Rearranges the admin menu to a more logical order
     *
     * @return void
     */
    public function rearrangeAdminMenu(): void
    {
        global $menu;

        remove_menu_page('edit.php');
        remove_menu_page('upload.php');
        remove_menu_page('edit.php?post_type=page');

        if (class_exists('ACF')) {
            remove_menu_page('edit.php?post_type=acf-field-group');
        }

        // Re-add the default Pages and Posts menu items
        add_menu_page('Pages', 'Pages', 'edit_pages', 'edit.php?post_type=page', '', 'dashicons-admin-page', 5);
        add_menu_page('Posts', 'Posts', 'edit_posts', 'edit.php', '', 'dashicons-admin-post', 6);

        // Move the Forms item to a lower position while respecting user capabilities.
        // $gravityFormsSlugs = ['gf_edit_forms', 'gf_entries', 'gf_settings', 'gf_addons', 'gf_new_form', 'gf_export', 'gf_system_status'];
        // $newFormsPosition = 16;

        // foreach ($menu as $key => $value) {
        //     if (isset($value[2]) && in_array($value[2], $gravityFormsSlugs)) {
        //         $gravityFormsItem = $menu[$key];
        //         unset($menu[$key]);
        //         $menu[$newFormsPosition] = $gravityFormsItem;
        //         break;
        //     }
        // }

        add_menu_page('Media', 'Media', 'upload_files', 'upload.php', '', 'dashicons-admin-media', 17);
        add_menu_page('Menus', 'Menu\'s', 'edit_theme_options', 'nav-menus.php', '', 'dashicons-list-view', 18);
    }

    /**
     * Add styling for the menu separators
     *
     * @return void
     */
    public function menuStyling(): void
    {
        echo '<style>
            #adminmenu .menu-separator {
                background-color: #131619;
                border-top: 1px solid #131619;
                color: white;
                padding-bottom: 3px;
                padding-top: 3px;
            }

            #adminmenu .menu-separator:hover {
                background-color: #131619;
                box-shadow: none;
                color: white;
                cursor: auto;
            }

            #adminmenu .menu-separator .wp-menu-name {
                font-size: 16px;
                font-weight: bold;
                padding-left: 5px;
            }

            #adminmenu .menu-separator .wp-menu-image.dashicons-before {
                display: none;
            }
        </style>';
    }

    /**
     * Remove 'settings -> media' menu item
     *
     * @return void
     */
    public function removeMediaMenuItem(): void
    {
        remove_submenu_page('options-general.php', 'options-media.php');
    }
}
