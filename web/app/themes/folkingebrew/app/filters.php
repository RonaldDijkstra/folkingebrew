<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * Add custom classes to body.
 *
 * @param array $classes
 * @return array
 */
add_filter('body_class', function ($classes) {
    // If on a WooCommerce page, use white background
    if (function_exists('is_woocommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
        $classes[] = 'at-the-top bg-white';
    } else {
        // Otherwise, add gradient background
        $classes[] = 'at-the-top bg-gradient-to-tr from-primary to-primary-dark';
    }

    // Add transparent header class if enabled
    if (get_field('transparent_header')) {
        $classes[] = 'transparent-header';
    }

    return $classes;
});

/**
 * Modify the main query for beers archive to use custom posts per page setting.
 *
 * @param \WP_Query $query
 * @return void
 */
add_action('pre_get_posts', function ($query) {
    // Only modify the main query on the frontend for beers archive
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('beers')) {
        $postsPerPage = get_field('posts_per_page', 'option') ?: 2;
        $query->set('posts_per_page', $postsPerPage);
    }
});

/**
 * Fix WooCommerce cart quantity validation - allow reducing/removing items without stock errors.
 * This only bypasses validation when quantity is being reduced or removed.
 */
// add_filter('woocommerce_update_cart_validation', function ($passed, $cart_item_key, $values, $quantity) {
//     // Allow removal (quantity = 0)
//     if ($quantity <= 0) {
//         return true;
//     }

//     $current_quantity = isset($values['quantity']) ? $values['quantity'] : 0;

//     // Allow reducing quantity without validation
//     if ($quantity < $current_quantity) {
//         return true;
//     }

//     // For increases, let WooCommerce handle normal validation
//     return $passed;
// }, 10, 4);

