<?php

namespace Custom\Setup\GravityForms\FieldRenderers;

class ConsentFieldRenderer extends BaseFieldRenderer
{
    public function supports(string $fieldType): bool
    {
        return $fieldType === 'consent';
    }

    public function getViewName(): string
    {
        return 'gravity.fields.consent';
    }

    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $viewModel = parent::buildViewModel($field, $value, $formId, $leadId);

        // Consent field specific properties
        $viewModel['formId'] = $formId;
        $viewModel['checkboxLabel'] = property_exists($field, 'checkboxLabel') ? $field->checkboxLabel : __('I consent', 'folkingebrew');
        $viewModel['consentVersion'] = class_exists('GFFormsModel') ? \GFFormsModel::get_latest_form_revisions_id($formId) : '';

        return $viewModel;
    }
}
