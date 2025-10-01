<?php

namespace Custom\Setup\GravityForms\FieldRenderers;

class HtmlFieldRenderer extends BaseFieldRenderer
{
    public function supports(string $fieldType): bool
    {
        return $fieldType === 'html';
    }

    public function getViewName(): string
    {
        return 'gravity.fields.html';
    }

    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $viewModel = parent::buildViewModel($field, $value, $formId, $leadId);

        // HTML field specific properties
        $viewModel['html'] = $field->content;

        return $viewModel;
    }
}
