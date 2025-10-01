<?php
namespace Custom\Setup\GravityForms\Services;

use Custom\Setup\ServiceInterface;
use Custom\Setup\GravityForms\Settings\SettingsManager;

class GravityFormsService implements ServiceInterface
{
    private FieldRenderingService $fieldRenderingService;
    private ValidationService $validationService;
    private FormProcessingService $formProcessingService;
    private AdminService $adminService;
    private ComponentService $componentService;
    private SettingsManager $settingsManager;

    public function __construct()
    {
        $this->fieldRenderingService = new FieldRenderingService();
        $this->validationService = new ValidationService();
        $this->formProcessingService = new FormProcessingService();
        $this->adminService = new AdminService();
        $this->componentService = new ComponentService();
        $this->settingsManager = new SettingsManager();
    }

    /**
     * Register all Gravity Forms services
     */
    public function register(): void
    {
        $this->fieldRenderingService->register();
        $this->validationService->register();
        $this->formProcessingService->register();
        $this->adminService->register();
        $this->componentService->register();
        $this->settingsManager->register();

        // Register confirmation rendering
        add_filter('gform_confirmation', [$this, 'renderConfirmation'], 10, 4);
    }

    /**
     * Render confirmation with Blade view
     */
    public function renderConfirmation($confirmation, $form, $entry): string
    {
        // Extract just the text from confirmation
        $confirmationText = is_string($confirmation)
            ? wp_strip_all_tags($confirmation)
            : ($confirmation['message'] ?? $confirmation);

        return view('gravity.confirmation', [
            'confirmation' => $confirmation,
            'confirmationText' => $confirmationText,
            'form' => $form,
            'entry' => $entry,
        ])->render();
    }

    /**
     * Get the field rendering service
     */
    public function getFieldRenderingService(): FieldRenderingService
    {
        return $this->fieldRenderingService;
    }

    /**
     * Get the validation service
     */
    public function getValidationService(): ValidationService
    {
        return $this->validationService;
    }

    /**
     * Get the form processing service
     */
    public function getFormProcessingService(): FormProcessingService
    {
        return $this->formProcessingService;
    }

    /**
     * Get the admin service
     */
    public function getAdminService(): AdminService
    {
        return $this->adminService;
    }

    /**
     * Get the component service
     */
    public function getComponentService(): ComponentService
    {
        return $this->componentService;
    }

    /**
     * Get the settings manager
     */
    public function getSettingsManager(): SettingsManager
    {
        return $this->settingsManager;
    }
}
