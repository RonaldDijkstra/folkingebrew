<?php

namespace Custom\Setup\CustomFields;

use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Location;

class PageOptions extends AbstractField
{
    /**
     * register field group
     */
    public function register_acf_field_group(): void
    {
        if (!function_exists('register_extended_field_group')) return;

        register_extended_field_group([
            'title' => 'Page Layout Options',
            'fields' => [
                TrueFalse::make('Transparent header', 'transparent_header')
                    ->stylized(),
                TrueFalse::make('Show ZenChef widget', 'show_zenchef_widget')
                    ->stylized(),
            ],
            'location' => [
                Location::where('post_type', 'page')
                    ->and('page_template', '!=', 'template-cart.blade.php'),
                Location::where('post_type', 'product')
            ],
            'position' => 'side',
            'menu_order' => 1,
            'layout' => 'block'
        ]);
    }
}
