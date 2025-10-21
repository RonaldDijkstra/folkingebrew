<?php
/**
 * Theme: Single product loader
 */

defined('ABSPATH') || exit;

/**
 * Keep core wrappers, breadcrumbs, and schema.
 * You can detach sub-actions in functions.php if you want to remove parts.
 */
// do_action('woocommerce_before_main_content');

/** Make sure $product is available to Blade as the global */
global $product;

/** Render Blade view */
echo \Roots\view('webshop.single-product')->render();

// do_action('woocommerce_after_main_content');
