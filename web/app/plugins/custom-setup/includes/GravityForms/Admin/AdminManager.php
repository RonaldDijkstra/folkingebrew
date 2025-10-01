<?php
namespace Custom\Setup\GravityForms\Admin;

class AdminManager
{
    private DateFieldSettings $dateFieldSettings;
    private FormSettingsManager $formSettingsManager;
    private EditorCustomizations $editorCustomizations;
    private ShowPagesWithForm $showPagesWithForm;

    public function __construct()
    {
        $this->dateFieldSettings = new DateFieldSettings();
        $this->formSettingsManager = new FormSettingsManager();
        $this->editorCustomizations = new EditorCustomizations();
        $this->showPagesWithForm = new ShowPagesWithForm();
        $this->registerHooks();
    }

    /**
     * Register WordPress hooks for admin functionality
     */
    private function registerHooks(): void
    {
        // Date field settings
        add_action('gform_field_standard_settings', [$this->dateFieldSettings, 'addDateFieldSettings'], 10, 2);
        add_filter('gform_tooltips', [$this->dateFieldSettings, 'addDateFieldTooltips']);
        add_action('gform_editor_js', [$this->dateFieldSettings, 'editorScript']);

        // Form settings
        add_action('gform_editor_js', [$this->formSettingsManager, 'hideProgressBarStyleSetting']);

        // Editor customizations
        add_filter('gform_add_field_buttons', [$this->editorCustomizations, 'removeFieldButtons']);
        add_filter('gform_field_groups_form_editor', [$this->editorCustomizations, 'removeFieldGroups']);

        // Show pages with form functionality
        $this->showPagesWithForm->register();
    }

    /**
     * Get the date field settings instance
     */
    public function getDateFieldSettings(): DateFieldSettings
    {
        return $this->dateFieldSettings;
    }

    /**
     * Get the form settings manager instance
     */
    public function getFormSettingsManager(): FormSettingsManager
    {
        return $this->formSettingsManager;
    }

    /**
     * Get the editor customizations instance
     */
    public function getEditorCustomizations(): EditorCustomizations
    {
        return $this->editorCustomizations;
    }

    /**
     * Get the show pages with form instance
     */
    public function getShowPagesWithForm(): ShowPagesWithForm
    {
        return $this->showPagesWithForm;
    }
}
