<?php

namespace Custom\Setup\GravityForms\FieldRenderers;

class NumberFieldRenderer extends BaseFieldRenderer
{
    public function supports(string $fieldType): bool
    {
        return $fieldType === 'number';
    }

    public function getViewName(): string
    {
        return 'gravity.fields.number';
    }

    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $viewModel = parent::buildViewModel($field, $value, $formId, $leadId);

        $viewModel['size'] = $field->size ?? '';
        $viewModel['min'] = $field->rangeMin ?? '';
        $viewModel['max'] = $field->rangeMax ?? '';

        // Build range instruction text if min or max is set
        $rangeText = '';
        $hasMin = !empty($field->rangeMin) && $field->rangeMin !== '';
        $hasMax = !empty($field->rangeMax) && $field->rangeMax !== '';

        if ($hasMin && $hasMax) {
            $rangeText = sprintf(
                __('Please enter a value between %s and %s.', 'folkingebrew'),
                $field->rangeMin,
                $field->rangeMax
            );
        } elseif ($hasMin) {
            $rangeText = sprintf(
                __('Please enter a value greater than or equal to %s.', 'folkingebrew'),
                $field->rangeMin
            );
        } elseif ($hasMax) {
            $rangeText = sprintf(
                __('Please enter a value less than or equal to %s.', 'folkingebrew'),
                $field->rangeMax
            );
        }

        $viewModel['rangeText'] = $rangeText;

        return $viewModel;
    }
}
