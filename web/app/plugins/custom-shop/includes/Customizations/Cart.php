<?php

namespace Custom\Shop\Customizations;

use Custom\Shop\ServiceInterface;

class Cart implements ServiceInterface
{
    public function register()
    {
        add_action('woocommerce_cart_calculate_fees', [$this, 'addDepositToCart'], 20);

        add_filter('woocommerce_shipping_rate_label', [$this, 'changeShippingLabel'], 10, 2);
    }

    /**
     * Change the shipping label to 'Shipping'.
     *
     * @param string $label
     * @param \WC_Shipping_Rate $rate
     * @return string
     */
    public function changeShippingLabel($label, $rate)
    {
        return __('Shipping', 'folkingebrew');
    }

    /**
     * Add the deposit to the cart.
     *
     * @param \WC_Cart $cart
     * @return void
     */
    public function addDepositToCart(\WC_Cart $cart)
    {
        if (is_admin() && !defined('DOING_AJAX')) {
            // Don't run in wp-admin product editors etc.
            return;
        }

        $totalDeposit = 0.0;

        foreach ($cart->get_cart() as $cartItemKey => $cartItem) {
            /** @var \WC_Product $product */
            $product = $cartItem['data'];
            if (!$product instanceof \WC_Product) {
                continue;
            }

            $qty = isset($cartItem['quantity']) ? (int) $cartItem['quantity'] : 1;

            // Get deposit, check product first
            $deposit = get_post_meta($product->get_id(), 'deposit_amount', true);

            // If this is a variation and you want variation-level override:
            if ($product->is_type('variation')) {
                $variationDeposit = get_post_meta($product->get_id(), 'deposit_amount', true);
                if ($variationDeposit !== '' && $variationDeposit !== null) {
                    $deposit = $variationDeposit;
                }
            }

            if ($deposit === '' || $deposit === null) {
                continue;
            }

            $deposit = (float) $deposit;
            if ($deposit <= 0) {
                continue;
            }

            $totalDeposit += ($deposit * $qty);
        }

        if ($totalDeposit > 0) {
            // Add fee (positive fee increases total)
            $cart->add_fee(
                __('Deposit', 'folkingebrew'),
                $totalDeposit,
                /* taxable? */ false
            );
        }
    }
}