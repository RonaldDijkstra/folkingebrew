<?php

namespace Custom\Setup\Admin;

use Custom\Setup\ServiceInterface;
use Custom\Setup\Helpers\FileLocator;

class Meta implements ServiceInterface
{
    public function register()
    {
        add_action('admin_head', [$this, 'customAdminFavicon']);
        add_action('login_head', [$this, 'customAdminFavicon']);
    }

    /** 
     * Use custom favicon when WP Admin is active
     * 
     * @return void
     */
    public function customAdminFavicon(): void
    {
        $iconUrl = FileLocator::getViteAssetUrl('resources/images/icon-folkingebrew-green.svg');

        echo '<link rel="shortcut icon" href="' . $iconUrl . '" />';
    }
}