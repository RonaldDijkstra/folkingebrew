<?php
namespace Custom\Setup\GravityForms\Processors;

interface FieldProcessorInterface
{
    /**
     * Process a form before submission
     *
     * @param array $form The form object
     * @return void
     */
    public function process(array $form): void;

    /**
     * Check if this processor supports the given field type
     *
     * @param string $fieldType The field type
     * @return bool True if supported
     */
    public function supports(string $fieldType): bool;

    /**
     * Get the processing priority (lower numbers run first)
     *
     * @return int The priority
     */
    public function getPriority(): int;
}
