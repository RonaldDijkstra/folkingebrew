<?php
namespace Custom\Setup\GravityForms\Services;

use Custom\Setup\GravityForms\Components\ComponentManager;

class ComponentService
{
    private ComponentManager $componentManager;

    public function __construct()
    {
        $this->componentManager = new ComponentManager();
    }

    /**
     * Register component hooks
     */
    public function register(): void
    {
        // ComponentManager registers hooks in its constructor, but let's make sure
        // by calling its register method if it has one
        if (method_exists($this->componentManager, 'register')) {
            $this->componentManager->register();
        }
    }

    /**
     * Get the component manager instance
     */
    public function getComponentManager(): ComponentManager
    {
        return $this->componentManager;
    }
}
