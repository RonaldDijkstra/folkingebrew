<?php 

namespace Custom\Setup\CustomFields;

use Extended\ACF\Location;
use Extended\ACF\ConditionalLogic;
use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Fields\Group;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\TimePicker;

class OptionsThePub extends AbstractField
{
    public function register_acf_field_group(): void
    {
        if (!function_exists('register_extended_field_group')) return;

        acf_add_options_sub_page('The Pub');

        register_extended_field_group([
            'title' => 'The Pub Options',
            'fields' => [
                Group::make('Opening Hours', 'the_pub_opening_hours')
                    ->fields([
                        Repeater::make('Opening Hours', 'hours')
                            ->fields([
                                Text::make('Week Day', 'day')
                                    ->column(20),
                                TrueFalse::make('Closed', 'is_closed')
                                    ->column(20),
                                TimePicker::make('Open', 'time_open')
                                    ->displayFormat('H')
                                    ->format('H')
                                    ->conditionalLogic([
                                        ConditionalLogic::where(
                                            name: 'is_closed',
                                            operator: '==',
                                            value: false,
                                        )
                                    ]),
                                TimePicker::make('Close', 'time_close')
                                    ->displayFormat('H')
                                    ->format('H')
                                    ->conditionalLogic([
                                        ConditionalLogic::where(
                                            name: 'is_closed',
                                            operator: '==',
                                            value: false,
                                        )
                                    ]),
                                TimePicker::make('Kitchen open', 'kitchen_open')
                                    ->displayFormat('H')
                                    ->format('H')
                                    ->conditionalLogic([
                                        ConditionalLogic::where(
                                            name: 'is_closed',
                                            operator: '==',
                                            value: false,
                                        )
                                    ]),
                                TimePicker::make('Kitchen close', 'kitchen_close')
                                    ->displayFormat('H')
                                    ->format('H')
                                    ->conditionalLogic([
                                        ConditionalLogic::where(
                                            name: 'is_closed',
                                            operator: '==',
                                            value: false,
                                        )
                                    ]),
                            ]),
                    ]),
            ],
            'style' => 'default',
            'location' => [
                Location::where('options_page', 'acf-options-the-pub')
            ],
        ]);
    }
}
