<?php

namespace Custom\Shop\CustomFields;

use Custom\Shop\ServiceInterface;
use Extended\ACF\Fields\Number;
use Extended\ACF\Location;

class Product implements ServiceInterface
{
    public function register()
    {
        add_action('acf/init', [$this, 'register_acf_field_group'], 20);
    }

    public function register_acf_field_group()
    {
        if (!function_exists('register_extended_field_group')) return;

        register_extended_field_group([
            'key' => 'group_custom_shop_product',
            'title' => 'Deposit',
            'fields' => [
                Number::make('Deposit', 'deposit_amount')
                    ->helperText('The deposit amount for the product. This is the amount that will be charged when the product is ordered.')
                    ->column(10)
                    ->step(0.01),
            ],
            'location' => [
                Location::where('post_type', '==', 'product'),
            ],
        ]);
    }
}
