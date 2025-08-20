<?php

namespace Custom\Setup\Setup;

use Custom\Setup\ServiceInterface;

class Theme implements ServiceInterface
{
    public function register()
    {
        add_action('after_setup_theme', [$this, 'setup']);
        add_action('init', [$this, 'setup_fallback']); // Fallback in case after_setup_theme missed
        add_action('wp_enqueue_scripts', [$this, 'dequeue_block_styles'], 100);
        add_action('enqueue_block_editor_assets', [$this, 'dequeue_block_styles'], 100);
    }


    public function setup()
    {
        error_log('Theme setup');
        remove_theme_support('wp-block-styles');
        remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
        remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
    }

    public function setup_fallback()
    {
        error_log('Theme setup fallback');
        remove_theme_support('wp-block-styles');
        remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
        remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
    }

    public function dequeue_block_styles()
    {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-block-style');
        wp_dequeue_style('global-styles');
        wp_dequeue_style('classic-theme-styles');
        
        // Also deregister to prevent re-enqueuing
        wp_deregister_style('wp-block-library');
        wp_deregister_style('wp-block-library-theme');
        wp_deregister_style('global-styles');
        wp_deregister_style('classic-theme-styles');
    }
}
