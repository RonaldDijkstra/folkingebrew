<?php
// BaseFieldRenderer.php
namespace Custom\Setup\GravityForms\FieldRenderers;

abstract class BaseFieldRenderer implements FieldRendererInterface
{
    /**
     * Build the base view model for all field types
     *
     * @param object $field The Gravity Forms field object
     * @param mixed $value The field value
     * @param int $formId The form ID
     * @param int $leadId The lead ID
     * @return array The base view model
     */
    protected function buildBaseViewModel(object $field, $value, int $formId, int $leadId): array
    {
        return [
            // Basics
            'visibility'   => $field->visibility ?? 'visible',
            'fieldId'      => (int) $field->id,
            'type'         => $field->type ?? '',
            'formId'       => $formId,

            // Label and help
            'label'        => $field->label ?? '',
            'isRequired'   => (bool) ($field->isRequired ?? false),
            'description'  => $field->description ?? '',

            // Value and choices
            'value'        => $value,
            'choices'      => (property_exists($field, 'choices') && is_array($field->choices)) ? $field->choices : [],

            // Validation state
            'failed'       => (bool) ($field->failed_validation ?? false),
            'message'      => $field->validation_message ?? '',

            // Common ids and names that match GF conventions
            'inputId'      => sprintf('input_%d_%d', $formId, (int) $field->id),
            'inputName'    => sprintf('input_%d', (int) $field->id),

            // Autocomplete
            'autocomplete' => $field->enableAutocomplete ?? false,
            'autocompleteAttribute' => $field->autocompleteAttribute ?? '',

            // Extra attributes
            'placeholder'  => $field->placeholder ?? '',
            'ariaDescId'   => sprintf('field_%d_%d_desc', $formId, (int) $field->id),
            'descriptionPlacement' => $field->descriptionPlacement ?? 'below',

            // Pass the full field object for access to type-specific properties
            'field'        => $field,
            'size'         => $field->size ?? 'large',
        ];
    }

    /**
     * Render the field using the view
     *
     * @param object $field The Gravity Forms field object
     * @param mixed $value The field value
     * @param int $formId The form ID
     * @param int $leadId The lead ID
     * @return string The rendered HTML
     */
    public function render(object $field, $value, int $formId, int $leadId): string
    {
        $viewModel = $this->buildViewModel($field, $value, $formId, $leadId);
        return view($this->getViewName(), $viewModel)->render();
    }

    /**
     * Build the complete view model for this field type
     * Override this method in subclasses to add type-specific data
     *
     * @param object $field The Gravity Forms field object
     * @param mixed $value The field value
     * @param int $formId The form ID
     * @param int $leadId The lead ID
     * @return array The complete view model
     */
    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        return $this->buildBaseViewModel($field, $value, $formId, $leadId);
    }
}
