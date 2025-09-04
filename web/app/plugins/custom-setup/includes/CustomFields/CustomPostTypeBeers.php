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
        if (!function_exists('acf_add_options_page') || !function_exists('register_extended_field_group')) {
            return;
        }

        acf_add_options_sub_page([
            'page_title' => 'Archive Options',
            'parent_slug' => 'acf-options',
            'parent_slug' => 'edit.php?post_type=beers',
            'menu_slug' => 'acf-options-beers',
        ]);

        register_extended_field_group([
            'title' => 'Beers',
            'key' => 'group_custom_post_type_beers',
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
                    ->format('array')
                    ->column(50)
                    ->required(),
                Image::make('Wallpaper', 'wallpaper')
                    ->format('array')
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

        register_extended_field_group([
            'title' => 'Archive Options',
            'key' => 'custom_post_type_beers_archive',
            'fields' => [
                Text::make('Archive Title', 'archive_title')
                    ->column(33)
                    ->required(),
                Number::make('Posts Per Page', 'posts_per_page')
                    ->step(1)
                    ->column(33)
                    ->required(),
            ],
            'location' => [
                Location::where('options_page', 'acf-options-beers'),
            ],
        ]);
    }
}
