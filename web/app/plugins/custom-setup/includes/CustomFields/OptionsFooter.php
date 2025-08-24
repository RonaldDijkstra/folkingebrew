<?php

namespace Custom\Setup\CustomFields;

use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Select;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\URL;
use Extended\ACF\Fields\WYSIWYGEditor;
use Extended\ACF\Location;

class OptionsFooter extends AbstractField
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

        acf_add_options_sub_page('Footer');

        register_extended_field_group([
            'title' => 'Footer Options',
            'fields' => [
                WYSIWYGEditor::make('Footer text', 'footer_text'),
            ],
            'style' => 'default',
            'location' => [
                Location::where('options_page', 'acf-options-footer')
            ],
        ]);
    }
}
