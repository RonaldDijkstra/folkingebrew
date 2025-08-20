<?php

namespace Custom\Setup\Setup;

use Custom\Setup\ServiceInterface;

class Security implements ServiceInterface
{   
    /** 
     * Register services
     * 
     * @return void
     */
    public function register()
    {
        add_filter('the_generator', array($this, 'hideWpVersion'));
        add_filter('rest_authentication_errors', [$this, 'restrictRestApiToLoggedInUsers']);
        add_action('after_setup_theme', [$this, 'disableRestApiFeatures']);
    }

    /**
     * Hide WP version (bloginfo('version'))
     *
     * @return string
     */
    public function hideWpVersion(): string 
    {
        return '';
    }

    /**
     * Restrict REST API to logged-in users, except for specific public CPTs
     *
     * @param mixed $result The existing response or `null`.
     * @return mixed Returns `WP_Error` if blocked, otherwise passes through `$result`.
     */
    public function restrictRestApiToLoggedInUsers(mixed $result): mixed
    {
        // Allow logged-in users
        if (is_user_logged_in()) {
            return $result;
        }

        // Block everything else
        return new \WP_Error(
            'rest_unauthorised',
            __('Only authenticated users can access the REST API.', 'rest_unauthorised'),
            ['status' => rest_authorization_required_code()]
        );
    }

    /**
     * Disable REST API & oEmbed features
     *
     * @return void
     */
    public function disableRestApiFeatures(): void
    {
        if (!is_admin()) {
            remove_action('wp_head', 'rest_output_link_wp_head', 10);
            remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
            remove_action('rest_api_init', 'wp_oembed_register_route');
            add_filter('embed_oembed_discover', '__return_false');
            remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
            remove_action('wp_head', 'wp_oembed_add_discovery_links');
            remove_action('wp_head', 'wp_oembed_add_host_js');
        }
    }
}