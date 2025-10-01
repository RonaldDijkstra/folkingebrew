<?php
namespace Custom\Setup\GravityForms\Services;

use Custom\Setup\GravityForms\Validators\ValidationManager;

class ValidationService
{
    private ValidationManager $validationManager;

    public function __construct()
    {
        $this->validationManager = new ValidationManager();
    }

    /**
     * Register validation hooks
     * Note: ValidationManager automatically registers hooks in its constructor
     */
    public function register(): void
    {
        // ValidationManager handles its own hook registration
        // This method is here for consistency with other services
    }

    /**
     * Get the validation manager instance
     */
    public function getValidationManager(): ValidationManager
    {
        return $this->validationManager;
    }
}
