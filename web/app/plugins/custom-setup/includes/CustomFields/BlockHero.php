<?php

namespace Custom\Setup\CustomFields;

use Extended\ACF\Fields\Text;
use Extended\ACF\Location;

class BlockHero extends AbstractField
{
    /**
     * register field group
     */
    public function register_acf_field_group(): void
    {
        if (!function_exists('acf_add_options_page')
        || !function_exists('register_extended_field_group')) {
            return;
        }

        register_extended_field_group([
            'title' => 'Hero Options',
            'fields' => [
                Text::make('Title', 'title'),
            ],
            'style' => 'default',
            'location' => [
                Location::where('block', '==', 'acf/hero')
            ],
        ]);
    }
}
