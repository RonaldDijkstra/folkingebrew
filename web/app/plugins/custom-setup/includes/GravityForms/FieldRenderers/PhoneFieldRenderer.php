<?php

namespace Custom\Setup\GravityForms\FieldRenderers;

class PhoneFieldRenderer extends BaseFieldRenderer
{
    public function supports(string $fieldType): bool
    {
        return $fieldType === 'phone';
    }

    public function getViewName(): string
    {
        return 'gravity.fields.phone';
    }

    /**
     * Build the complete view model for phone fields
     *
     * @param object $field The Gravity Forms field object
     * @param mixed $value The field value
     * @param int $formId The form ID
     * @param int $leadId The lead ID
     * @return array The complete view model
     */
    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $baseViewModel = $this->buildBaseViewModel($field, $value, $formId, $leadId);

        // Add phone-specific data
        $baseViewModel['inputType'] = 'tel';
        $baseViewModel['autocompleteValue'] = $this->getAutocompleteValue($field);

        return $baseViewModel;
    }

    /**
     * Determine the appropriate autocomplete value for the phone field
     *
     * @param object $field The Gravity Forms field object
     * @return string The autocomplete value
     */
    private function getAutocompleteValue(object $field): string
    {
        // Handle autocomplete attribute - check for phoneFormat or other autocomplete-related properties
        $autocompleteValue = 'tel'; // Default fallback

        // Gravity Forms may use different property names, check common ones
        if (property_exists($field, 'phoneFormat') && !empty($field->phoneFormat)) {
            // Map phone formats to appropriate autocomplete values
            switch ($field->phoneFormat) {
                case 'domestic':
                    $autocompleteValue = 'tel-national';
                    break;
                case 'international':
                    $autocompleteValue = 'tel';
                    break;
                default:
                    $autocompleteValue = $field->phoneFormat;
                    break;
            }
        } elseif (property_exists($field, 'autocomplete') && !empty($field->autocomplete)) {
            $autocompleteValue = $field->autocomplete;
        }

        return $autocompleteValue;
    }
}