<?php

namespace Custom\Setup\Admin;

use Custom\Setup\ServiceInterface;
use Custom\Setup\Helpers\FileLocator;

class Page implements ServiceInterface
{
    public function register()
    {
        add_action('login_head', [$this, 'customLoginLogo']);
        add_filter('login_headerurl', [$this, 'customLoginLogoURL']);
        add_filter('login_headertext', [$this, 'customLoginLogoLinkTitle']);
    }

    public function customLoginLogo(): void
    {
        $logoUrl = FileLocator::getViteAssetUrl('resources/images/logo-folkingebrew.svg');
        // $path = "/app/themes/folkingebrew/public/build/$logo";
        $width = "240px";
        $height = "113px";

        echo "
            <style>
                body.login #login h1 a {
                    background: url('$logoUrl') no-repeat scroll center top transparent;
                    background-size: 100%;
                    height: $height;
                    width: $width;
                }
            </style>
            ";
    }

    /** 
     * Link the login logo to the current website
     * 
     * @return string
     */
    public function customLoginLogoURL(): string
    {
        return get_bloginfo('url');
    }

    /**
     * Set custom login logo title
     *
     * @return string
     */
    public function customLoginLogoLinkTitle(): string
    {
        return 'Folkingebrew';
    }
}
