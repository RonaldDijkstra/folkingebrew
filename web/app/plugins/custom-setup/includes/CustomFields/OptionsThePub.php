<?php 

namespace Custom\Setup\CustomFields;

use Extended\ACF\Location;
use Extended\ACF\ConditionalLogic;
use Extended\ACF\Fields\TrueFalse;
use Extended\ACF\Fields\Group;
use Extended\ACF\Fields\Link;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Fields\TimePicker;
use Extended\ACF\Fields\DatePicker;
use Extended\ACF\Fields\WYSIWYGEditor;

class OptionsThePub extends AbstractField
{
    public function register_acf_field_group(): void
    {
        if (!function_exists('register_extended_field_group')) return;

        acf_add_options_sub_page('The Pub');

        register_extended_field_group([
            'title' => 'The Pub Options',
            'fields' => [
                Group::make('Company Details', 'company_details')
                    ->fields([
                        Text::make('Name', 'company_name'),
                        Text::make('Address', 'company_address'),
                        Text::make('Zipcode', 'company_zipcode'),
                        Text::make('City', 'company_city'),
                        Text::make('Phone', 'company_phone'),
                        Text::make('Whatsapp', 'company_whatsapp'),
                        Text::make('Email', 'company_email'),
                        Text::make('Facebook', 'company_facebook'),
                        Text::make('Instagram', 'company_instagram'),
                        Text::make('Google Maps', 'company_google_maps'),
                    ])
                    ->layout('row'),
                Group::make('Directions', 'directions')
                    ->fields([
                        WYSIWYGEditor::make('Public Transport', 'public_transport'),
                        WYSIWYGEditor::make('Car', 'car'),
                    ])
                    ->layout('row'),
                Repeater::make('Opening Hours', 'hours')
                    ->fields([
                        Text::make('Week Day', 'day')
                            ->column(20),
                        TrueFalse::make('Closed', 'is_closed')
                            ->column(20),
                        TimePicker::make('Open', 'time_open')
                            ->displayFormat('H:i')
                            ->format('H')
                            ->conditionalLogic([
                                ConditionalLogic::where(
                                    name: 'is_closed',
                                    operator: '==',
                                    value: false,
                                )
                            ]),
                        TimePicker::make('Close', 'time_close')
                            ->displayFormat('H:i')
                            ->format('H')
                            ->conditionalLogic([
                                ConditionalLogic::where(
                                    name: 'is_closed',
                                    operator: '==',
                                    value: false,
                                )
                            ]),
                        TimePicker::make('Kitchen open', 'kitchen_open')
                            ->displayFormat('H:i')
                            ->format('H')
                            ->conditionalLogic([
                                ConditionalLogic::where(
                                    name: 'is_closed',
                                    operator: '==',
                                    value: false,
                                )
                            ]),
                        TimePicker::make('Kitchen close', 'kitchen_close')
                            ->displayFormat('H:i')
                            ->format('H')
                            ->conditionalLogic([
                                ConditionalLogic::where(
                                    name: 'is_closed',
                                    operator: '==',
                                    value: false,
                                )
                            ]),
                    ]),
                    
                Repeater::make('Events', 'events')
                    ->fields([
                        DatePicker::make('Date', 'date')
                            ->helperText('Add the date of the event.')
                            ->displayFormat('d-m')
                            ->format('d-m-Y')
                            ->defaultNow()
                            ->required(),
                        Text::make('Title', 'title'),
                        Text::make('Subtitle', 'description'),
                        Link::make('Link', 'link'),
                    ]),
            ],
            'style' => 'default',
            'location' => [
                Location::where('options_page', 'acf-options-the-pub')
            ],
        ]);
    }
}
