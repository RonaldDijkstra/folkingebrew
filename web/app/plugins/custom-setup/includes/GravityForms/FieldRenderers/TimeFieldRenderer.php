<?php

namespace Custom\Setup\GravityForms\FieldRenderers;

class TimeFieldRenderer extends BaseFieldRenderer
{
    public function supports(string $fieldType): bool
    {
        return $fieldType === 'time';
    }

    public function getViewName(): string
    {
        return 'gravity.fields.time';
    }

    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $viewModel = parent::buildViewModel($field, $value, $formId, $leadId);

        // Time field specific properties
        $viewModel['timeFormat'] = property_exists($field, 'timeFormat') ? $field->timeFormat : '12';

        return $viewModel;
    }
}
