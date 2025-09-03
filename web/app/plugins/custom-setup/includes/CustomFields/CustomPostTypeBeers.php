<?php

namespace Custom\Setup\CustomFields;

use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\Number;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\WYSIWYGEditor;
use Extended\ACF\Location;

class CustomPostTypeBeers extends AbstractField
{
    public function register_acf_field_group(): void
    {
        register_extended_field_group([
            'title' => 'Beers',
            'fields' => [
                Number::make('ID', 'id')
                    ->step(1)
                    ->column(33)
                    ->required(),
                Text::make('Style', 'style')
                    ->column(33)
                    ->required(),
                Number::make('Abv', 'abv')
                    ->column(33)
                    ->step(0.1)
                    ->required(),
                Image::make('Image', 'image')
                    ->column(50)
                    ->required(),
                Image::make('Wallpaper', 'wallpaper')
                    ->column(50)
                    ->required(),
                WYSIWYGEditor::make('Description', 'description')
                    ->column(100)
                    ->required(),
                Repeater::make('Specs', 'specs')
                    ->layout('row')
                    ->button('Add Spec')
                    ->fields([
                        Text::make('Key', 'key')
                            ->required(),
                        Text::make('Value', 'value')
                            ->required(),
                    ]),
                Link::make('Untappd Link', 'untappd_link')
                    ->column(100),
            ],
            'location' => [
                Location::where('post_type', '==', 'beers'),
            ],
        ]);
    }
}
