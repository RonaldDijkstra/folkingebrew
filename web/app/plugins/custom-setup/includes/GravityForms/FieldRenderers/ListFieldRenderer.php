<?php

namespace Custom\Setup\GravityForms\FieldRenderers;

class ListFieldRenderer extends BaseFieldRenderer
{
    public function supports(string $fieldType): bool
    {
        return $fieldType === 'list';
    }

    public function getViewName(): string
    {
        return 'gravity.fields.list';
    }

    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $viewModel = parent::buildViewModel($field, $value, $formId, $leadId);

        // List field specific properties
        $viewModel['maxRows'] = property_exists($field, 'maxRows') ? (int) $field->maxRows : 0;
        $viewModel['enableColumns'] = property_exists($field, 'enableColumns') ? (bool) $field->enableColumns : false;
        $viewModel['listFields'] = property_exists($field, 'fields') ? $field->fields : [];

        return $viewModel;
    }
}
