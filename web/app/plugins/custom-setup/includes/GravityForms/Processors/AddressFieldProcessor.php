<?php
namespace Custom\Setup\GravityForms\Processors;

class AddressFieldProcessor implements FieldProcessorInterface
{
    /**
     * Process address fields before submission
     * Normalize address field country codes to country names for consistency
     */
    public function process(array $form): void
    {
        if (empty($form['fields']) || !function_exists('rgpost')) {
            return;
        }

        foreach ($form['fields'] as $field) {
            if (($field->type ?? '') !== 'address') {
                continue;
            }

            // Get the country list for this field
            $countries = $field->get_countries();
            if (empty($countries) || !is_array($countries)) {
                continue;
            }

            // Check each address sub-input for country field (suffix 6)
            $fid = (int) $field->id;
            $countryInputName = 'input_' . $fid . '_6';
            $countryValue = rgpost($countryInputName);

            if ($countryValue !== null && $countryValue !== '') {
                // If the value is a country code, convert it to the country name
                if (isset($countries[$countryValue])) {
                    $_POST[$countryInputName] = $countries[$countryValue];
                }
                // If it's already a country name, leave it as is
            }
        }
    }

    /**
     * Check if this processor supports the given field type
     */
    public function supports(string $fieldType): bool
    {
        return $fieldType === 'address';
    }

    /**
     * Get the processing priority
     */
    public function getPriority(): int
    {
        return 30;
    }
}
