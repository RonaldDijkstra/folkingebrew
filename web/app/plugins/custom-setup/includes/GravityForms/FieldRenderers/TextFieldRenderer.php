<?php

namespace Custom\Setup\GravityForms\FieldRenderers;

class TextFieldRenderer extends BaseFieldRenderer
{
    public function supports(string $fieldType): bool
    {
        return in_array($fieldType, ['text', 'email', 'number', 'website', 'phone']);
    }

    public function getViewName(): string
    {
        return 'gravity.fields.text';
    }

    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $viewModel = parent::buildViewModel($field, $value, $formId, $leadId);

        // Add HTML input type based on field type
        $viewModel['htmlInputType'] = $this->getHtmlInputType($field->type ?? '');

        return $viewModel;
    }

    private function getHtmlInputType(string $fieldType): string
    {
        return match($fieldType) {
            'email' => 'email',
            'number' => 'number',
            'website' => 'url',
            'phone' => 'tel',
            default => 'text'
        };
    }
}
