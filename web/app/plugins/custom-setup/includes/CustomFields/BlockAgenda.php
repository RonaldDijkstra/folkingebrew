<?php

namespace Custom\Setup\CustomFields;

use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\DatePicker;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\Link;
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
                Text::make('Subtitle', 'subtitle'),
                Repeater::make('Agenda Items', 'agenda_items')
                    ->fields([
                        Text::make('Title', 'title'),
                        Text::make('Subtitle', 'description'),
                        DatePicker::make('Date', 'date')
                            ->helperText('Add the date of the event.')
                            ->displayFormat('d/m/Y')
                            ->format('d/m/Y')
                            ->defaultNow()
                            ->required(),
                        Link::make('Link', 'link'),
                    ])
                    ->layout('block')
                    ->button('Add Agenda Item'),
            ],
            'style' => 'default',
            'location' => [
                Location::where('block', '==', 'acf/agenda')
            ],
        ]);
    }
}