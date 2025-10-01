<?php
namespace Custom\Setup\GravityForms\FieldRenderers;

class DateFieldRenderer extends BaseFieldRenderer
{
    public function supports(string $fieldType): bool
    {
        return $fieldType === 'date';
    }

    public function getViewName(): string
    {
        return 'gravity.fields.date';
    }

    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $viewModel = parent::buildViewModel($field, $value, $formId, $leadId);

        // Custom date field settings
        $viewModel['noPastDates'] = (bool) (property_exists($field, 'noPastDates') ? $field->noPastDates : false);

        return $viewModel;
    }
}
