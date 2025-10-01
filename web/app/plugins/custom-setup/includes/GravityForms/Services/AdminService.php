<?php
namespace Custom\Setup\GravityForms\Services;

use Custom\Setup\GravityForms\Admin\AdminManager;

class AdminService
{
    private AdminManager $adminManager;

    public function __construct()
    {
        $this->adminManager = new AdminManager();
    }

    /**
     * Register admin hooks
     * Note: AdminManager automatically registers hooks in its constructor
     */
    public function register(): void
    {
        // AdminManager handles its own hook registration
        // This method is here for consistency with other services
    }

    /**
     * Get the admin manager instance
     */
    public function getAdminManager(): AdminManager
    {
        return $this->adminManager;
    }
}
