<?php

namespace Custom\Setup\Admin;

use Custom\Setup\ServiceInterface;

class Dashboard implements ServiceInterface
{
    public function register()
    {
        add_action('admin_head', [$this, 'customAdminDashboardTitle']);
        add_action('admin_head', [$this, 'removeDashboardWidgets'], 100);
    }

     /**
     * Customize the dashboard title with the current user's name
     */
    public function customAdminDashboardTitle(): void
    {
        if ($GLOBALS['title'] != 'Dashboard') {
            return;
        }

        $currentUser = wp_get_current_user();
        $userName = $currentUser->display_name;

        $GLOBALS['title'] = sprintf(__('Moi, %s!', 'custom_setup'), $userName);
    }

    /**
     * Remove default dashboard meta boxes
     */
    public function removeDashboardWidgets(): void
    {
        remove_action('welcome_panel', 'wp_welcome_panel');

        $widgets = [
            'dashboard_site_health',
            'dashboard_right_now',
            'dashboard_activity',
            'dashboard_quick_press',
            'dashboard_primary',
            'dashboard_secondary',
            'dashboard_recent_comments',
            'dashboard_recent_drafts',
            'dashboard_plugins',
        ];

        foreach ($widgets as $id) {
            remove_meta_box($id, 'dashboard', 'normal');
            remove_meta_box($id, 'dashboard', 'side');
            remove_meta_box($id, 'dashboard', 'advanced');
        }
    }
}
