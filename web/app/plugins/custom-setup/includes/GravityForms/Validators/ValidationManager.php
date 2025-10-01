<?php
namespace Custom\Setup\GravityForms\Validators;

class ValidationManager
{
    private array $validators = [];

    public function __construct()
    {
        $this->registerDefaultValidators();
        $this->registerHooks();
    }

    /**
     * Register the default validators
     */
    private function registerDefaultValidators(): void
    {
        $this->registerValidator(new DateValidator());
    }

    /**
     * Register a validator
     */
    public function registerValidator(FieldValidatorInterface $validator): void
    {
        $this->validators[] = $validator;

        // Sort validators by priority
        usort($this->validators, function($a, $b) {
            return $a->getPriority() <=> $b->getPriority();
        });
    }

    /**
     * Register WordPress hooks for validation
     */
    private function registerHooks(): void
    {
        // Register field-level validation
        add_filter('gform_field_validation', [$this, 'validateField'], 10, 4);

        // Register form-level validation for additional checks
        add_filter('gform_validation', [$this, 'validateForm'], 10, 1);
    }

    /**
     * Validate a single field
     */
    public function validateField(array $result, $value, array $form, object $field): array
    {
        $fieldType = $field->type ?? '';

        foreach ($this->validators as $validator) {
            if ($validator->supports($fieldType)) {
                $result = $validator->validate($result, $value, $form, $field);

                // If validation failed, stop processing other validators
                if (!$result['is_valid']) {
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Validate the entire form (for additional checks that need form context)
     */
    public function validateForm(array $validation_result): array
    {
        if (!is_array($validation_result) || empty($validation_result['form']['fields'])) {
            return $validation_result;
        }

        $fieldType = 'date'; // For now, only date validation needs form-level checks

        foreach ($this->validators as $validator) {
            if ($validator->supports($fieldType) && method_exists($validator, 'enforceNoPastDatesOnDatefields')) {
                $validation_result = $validator->enforceNoPastDatesOnDatefields($validation_result);
            }
        }

        return $validation_result;
    }

    /**
     * Get all registered validators
     */
    public function getValidators(): array
    {
        return $this->validators;
    }

    /**
     * Get validators that support a specific field type
     */
    public function getValidatorsForFieldType(string $fieldType): array
    {
        return array_filter($this->validators, function($validator) use ($fieldType) {
            return $validator->supports($fieldType);
        });
    }
}
