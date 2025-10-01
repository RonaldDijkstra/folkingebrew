<?php

namespace Custom\Setup\GravityForms\FieldRenderers;

interface FieldRendererInterface
{
    /**
     * Render the field using the appropriate view
     *
     * @param object $field The Gravity Forms field object
     * @param mixed $value The field value
     * @param int $formId The form ID
     * @param int $leadId The lead ID
     * @return string The rendered HTML
     */
    public function render(object $field, $value, int $formId, int $leadId): string;

    /**
     * Check if this renderer supports the given field type
     *
     * @param string $fieldType The field type
     * @return bool True if supported
     */
    public function supports(string $fieldType): bool;

    /**
     * Get the view name for this field type
     *
     * @return string The Blade view name
     */
    public function getViewName(): string;
}
