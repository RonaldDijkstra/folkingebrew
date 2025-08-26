<?php

namespace Custom\Setup\CustomFields;

use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\RadioButton;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Fields\WYSIWYGEditor;
use Extended\ACF\Location;

class BlockContent extends AbstractField
{
    public function register_acf_field_group(): void
    {
        if (!function_exists('acf_add_options_page')
        || !function_exists('register_extended_field_group')) {
            return;
        }
        register_extended_field_group([
            'title' => 'Content Options',
            'fields' => [
                RadioButton::make('Background color', 'background_color')
                    ->choices([
                        'bg-white' => 'White',
                        'bg-neutral-light-brown' => 'Neutral light brown',
                    ])
                    ->default('white'),
                TrueFalse::make('Reverse layout?', 'text_right')
                    ->stylized(),
                Text::make('Title', 'title'),
                WYSIWYGEditor::make('Text', 'text'),
                Link::make('Button', 'link'),
                Image::make('Image', 'image')
                    ->format('array')
                    ->previewSize('medium'),
            ],
            'style' => 'default',
            'location' => [
                Location::where('block', '==', 'acf/content')
            ],
        ]);
    }
}
