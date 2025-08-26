<?php

namespace Custom\Setup\CustomFields;

use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\RadioButton;
use Extended\ACF\Fields\Repeater;
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
                RadioButton::make('Background Type', 'background_type')
                    ->choices([
                        'primary' => 'Primary',
                        'pub' => 'The Pub',
                    ])
                    ->default('primary'),
                Image::make('Background Image', 'background_image')
                    ->acceptedFileTypes(['jpg']),
                Image::make('Image', 'image')
                    ->acceptedFileTypes(['svg']),
                Text::make('Title', 'title'),
                Repeater::make('Buttons', 'buttons')
                    ->layout('row')
                    ->fields([
                        Link::make('Link', 'link'),
                        RadioButton::make('Type', 'type')
                            ->choices([
                                'primary' => 'Primary',
                                'outline' => 'Outline White',
                                'outline-primary' => 'Outline Primary',
                            ])
                            ->default('primary'),
                    ]),
            ],
            'style' => 'default',
            'location' => [
                Location::where('block', '==', 'acf/hero')
            ],
        ]);
    }
}
