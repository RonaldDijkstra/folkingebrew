<?php

namespace Custom\Shop\Settings;

use Custom\Shop\ServiceInterface;

class Settings implements ServiceInterface
{
    public function register(): void
    {
        // Disable WooCommerce’s legacy CSS everywhere because it hurts our logos and other styles.
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');

        add_action('woocommerce_init', function () {
            if (!function_exists('woocommerce_register_additional_checkout_field')) {
                return;
            }
            woocommerce_register_additional_checkout_field([
                'id'       => 'folkingebrew/subscribe-to-newsletter',
                'label'    => __('Subscribe to newsletter', 'folkingebrew'),
                'location' => 'order', // 'contact' | 'address' | 'order'
                'type'     => 'checkbox',  // 'text' | 'select' | 'checkbox'
                'required' => false,
            ]);
        });

        add_action('wp_enqueue_scripts', function () {
            // Example: if the floating-label script is named 'floating-labels'
            wp_dequeue_script('floating-labels');
        }, 20);
    }
}
