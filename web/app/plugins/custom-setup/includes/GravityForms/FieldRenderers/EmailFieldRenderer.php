<?php

namespace Custom\Setup\GravityForms\FieldRenderers;

class EmailFieldRenderer extends BaseFieldRenderer
{
    public function supports(string $fieldType): bool
    {
        return $fieldType === 'email';
    }

    public function getViewName(): string
    {
        return 'gravity.fields.email';
    }

    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $viewModel = parent::buildViewModel($field, $value, $formId, $leadId);

        $viewModel['type'] = 'email';

        return $viewModel;
    }
}
