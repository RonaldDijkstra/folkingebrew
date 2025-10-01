<?php
namespace Custom\Setup\GravityForms\Services;

use Custom\Setup\GravityForms\Processors\FormProcessorManager;

class FormProcessingService
{
    private FormProcessorManager $processorManager;

    public function __construct()
    {
        $this->processorManager = new FormProcessorManager();
    }

    /**
     * Register form processing hooks
     * Note: FormProcessorManager automatically registers hooks in its constructor
     */
    public function register(): void
    {
        // FormProcessorManager handles its own hook registration
        // This method is here for consistency with other services
    }

    /**
     * Get the processor manager instance
     */
    public function getProcessorManager(): FormProcessorManager
    {
        return $this->processorManager;
    }
}
