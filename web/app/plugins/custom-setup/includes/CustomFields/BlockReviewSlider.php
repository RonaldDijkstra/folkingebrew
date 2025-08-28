<?php 

namespace Custom\Setup\CustomFields;

use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Textarea;
use Extended\ACF\Fields\Number;
use Extended\ACF\Fields\Repeater;
use Extended\ACF\Location;

class BlockReviewSlider extends AbstractField
{
    public function register_acf_field_group(): void
    {
        if (!function_exists('register_extended_field_group')) return;

        register_extended_field_group([
            'title' => 'Review Slider',
            'fields' => [
                Text::make('Title', 'title'),
                Text::make('Subtitle', 'subtitle'),
                Repeater::make('Reviews', 'reviews')
                    ->fields([
                        Text::make('Name', 'name'),
                        Textarea::make('Review', 'review'),
                        Number::make('Rating', 'rating')
                            ->min(1)
                            ->max(5),
                        Text::make('Review Source', 'source'),
                    ])
                    ->layout('block')
                    ->button('Add Review'),
            ],
            'style' => 'default',
            'location' => [
                Location::where('block', '==', 'acf/review-slider')
            ],
        ]);
    }
}
