<?php
/**
 * Plugin Name:     Custom Setup
 * Description:     Plugin with custom setup for the site.
 * Author:          Folkingebrew
 * Version:         0.0.1
 * Text Domain:     custom-setup
 * Domain Path:     /languages
 * License:         GPLv2
 *
 * @package         Custom_Setup
 */

use Custom\Setup\Base\Activate;
use Custom\Setup\Base\Deactivate;

// If this file is called directly, abort!
defined('ABSPATH') or die("You can't access this file directly.");

// Define constants.
if (!defined('CUSTOM_THEME_SETUP_DIR_URL')) {
    define('CUSTOM_THEME_SETUP_DIR_URL', plugin_dir_url(__FILE__));
}

if (!defined('CUSTOM_THEME_SETUP_DIR_PATH')) {
    define('CUSTOM_THEME_SETUP_DIR_PATH', plugin_dir_path(__FILE__));
}

// Plugin activation and deactivation.
register_activation_hook(__FILE__, function() {
    Activate::activate();
});
register_deactivation_hook(__FILE__, function() {
    Deactivate::deactivate();
});

 // Initialize all the core classes of the plugin.
add_action('init', function() {
    if (class_exists('Custom\\Setup\\Init')) {
        Custom\Setup\Init::register_services();
    }
}, 5);
