<?php
namespace Custom\Setup\GravityForms\Validators;

interface FieldValidatorInterface
{
    /**
     * Validate a field value
     *
     * @param array $result The validation result array
     * @param mixed $value The field value to validate
     * @param array $form The form object
     * @param object $field The field object
     * @return array The validation result array
     */
    public function validate(array $result, $value, array $form, object $field): array;

    /**
     * Check if this validator supports the given field type
     *
     * @param string $fieldType The field type
     * @return bool True if supported
     */
    public function supports(string $fieldType): bool;

    /**
     * Get the validation priority (lower numbers run first)
     *
     * @return int The priority
     */
    public function getPriority(): int;
}
