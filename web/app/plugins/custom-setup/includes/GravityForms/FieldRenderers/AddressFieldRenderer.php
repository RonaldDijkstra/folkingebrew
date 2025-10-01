<?php

namespace Custom\Setup\GravityForms\FieldRenderers;

class AddressFieldRenderer extends BaseFieldRenderer
{
    public function supports(string $fieldType): bool
    {
        return $fieldType === 'address';
    }

    public function getViewName(): string
    {
        return 'gravity.fields.address';
    }

    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $viewModel = parent::buildViewModel($field, $value, $formId, $leadId);

        // Address field specific properties
        $viewModel['countries'] = $field->get_countries();

        return $viewModel;
    }
}
