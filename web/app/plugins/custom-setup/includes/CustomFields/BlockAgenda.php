<?php

namespace Custom\Setup\CustomFields;

use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Image;
use Extended\ACF\Location;

class BlockAgenda extends AbstractField {

    public function register_acf_field_group(): void
    {
        if (!function_exists('register_extended_field_group')) return;

        register_extended_field_group([
            'title' => 'Agenda',
            'fields' => [
                Image::make('Background Image', 'background_image'),
                Text::make('Title', 'title'),
                Text::make('Subtitle', 'subtitle')
            ],
            'style' => 'default',
            'location' => [
                Location::where('block', '==', 'acf/agenda')
            ],
        ]);
    }
}