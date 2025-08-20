<?php

namespace Custom\Setup\Setup;

use Custom\Setup\ServiceInterface;

class Performance implements ServiceInterface
{   
    /** 
     * Register Services
     * 
     * @return void
     */
    public function register()
    {
        add_action('wp_enqueue_scripts', array($this, 'removeBlockLibraryStyles'));
        add_filter('wp_lazy_loading_enabled', '__return_false');
    
        if (!is_admin()) {
            add_filter('script_loader_tag', array($this, 'addDeferAttribute'), 10);
            add_filter('style_loader_tag', array($this, 'removeStyleId'), 10);
        }
    }

    /**
     * Add defer attribute to scripts
     *
     * @param string $tag
     * @return string
     */
    public function addDeferAttribute(string $tag): string
    {   
        // Let's not add defer to wp-include scripts
        if (strpos($tag, 'src=') !== false && strpos($tag, 'wp-includes') !== false) {
            return $tag;
        }

        // Let's not add defer to these critical scripts
        $scriptsToExclude = [
            'app.js',
            'jquery.js',
        ];

        foreach ($scriptsToExclude as $excludeScript) {
            if (stripos($tag, $excludeScript) !== false) {
                return $tag;
            }
        }

        // For all other scripts without async nor defer, add defer
        if (!preg_match('/(?:async|defer)/i', $tag)) {
            return str_replace(' src', ' defer src', $tag);
        }

        return $tag;
    }
    
    /**
     * Remove block library styles
     *
     * @param string $tag
     * @return string
     */
    public function removeBlockLibraryStyles(): void
    {
        wp_dequeue_style('wp-block-library');
        wp_deregister_style('wp-block-library');
    }

    /**
     * Remove id from style tags
     *
     * @param string $link
     * @return string
     */
    public function removeStyleId(string $link): string
    {
        return preg_replace("/ id='.*-css'/", "", $link);
    }
}