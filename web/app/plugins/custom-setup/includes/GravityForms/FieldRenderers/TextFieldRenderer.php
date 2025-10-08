<?php

namespace Custom\Setup\GravityForms\FieldRenderers;

class TextFieldRenderer extends BaseFieldRenderer
{
    public function supports(string $fieldType): bool
    {
        return in_array($fieldType, ['text', 'website', 'phone']);
    }

    public function getViewName(): string
    {
        return 'gravity.fields.text';
    }

    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $viewModel = parent::buildViewModel($field, $value, $formId, $leadId);

        $viewModel['type'] = $this->getHtmlInputType($field->type ?? '');

        return $viewModel;
    }

    private function getHtmlInputType(string $fieldType): string
    {
        return match($fieldType) {
            'website' => 'url',
            'phone' => 'tel',
            default => 'text'
        };
    }
}
