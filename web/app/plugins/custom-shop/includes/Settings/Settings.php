<?php

namespace Custom\Shop\Settings;

use Custom\Shop\ServiceInterface;

class Settings implements ServiceInterface
{
    public function register(): void
    {
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    }
}
