<?php

namespace Custom\Setup\Setup;

use Custom\Setup\ServiceInterface;
use WP_Query;

class Search implements ServiceInterface
{
    public function register(): void
    {
        add_action('template_redirect', [$this, 'redirectSearch']);
        add_action('widgets_init', [$this, 'unregisterSearchWidget']);
        add_filter('get_search_form', [$this, 'emptySearchForm']);
        add_action('pre_get_posts', [$this, 'disableSearch']);
    }

    public function redirectSearch(): void
    {
        // Catch real searches and any request that sets ?s= even if empty
        if (is_search() || isset($_GET['s'])) {
            wp_redirect(home_url(), 301);
            exit;
        }
    }

    public function unregisterSearchWidget(): void
    {
        unregister_widget('WP_Widget_Search');
    }

    public function emptySearchForm(): string
    {
        return '';
    }

    public function disableSearch(WP_Query $query): void
    {
        // Only front end main query
        if (!is_admin() && $query->is_main_query() && $query->is_search()) {
            $query->is_search = false;
            $query->set('s', null);
        }
    }
}