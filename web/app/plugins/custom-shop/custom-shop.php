<?php
/**
 * Plugin Name:     Custom Shop
 * Description:     Plugin with custom setup for the shop.
 * Author:          Folkingebrew
 * Version:         0.0.1
 * Text Domain:     custom-shop
 * Domain Path:     /languages
 * License:         GPLv2
 *
 * @package         Custom_Shop
 */

use Custom\Shop\Base\Activate;
use Custom\Shop\Base\Deactivate;

// If this file is called directly, abort!
defined('ABSPATH') or die("You can't access this file directly.");

// Define constants.
if (!defined('CUSTOM_THEME_SHOP_DIR_URL')) {
    define('CUSTOM_THEME_SHOP_DIR_URL', plugin_dir_url(__FILE__));
}

if (!defined('CUSTOM_THEME_SHOP_DIR_PATH')) {
    define('CUSTOM_THEME_SHOP_DIR_PATH', plugin_dir_path(__FILE__));
}

// Plugin activation and deactivation.
register_activation_hook(__FILE__, function() {
    Activate::activate();
});
register_deactivation_hook(__FILE__, function() {
    Deactivate::deactivate();
});

 // Initialize all the core classes of the plugin.
 add_action('after_setup_theme', function() {
    if (class_exists('Custom\\Shop\\Init')) {
        Custom\Shop\Init::register_services();
    }
}, 5);
