<?php

namespace Custom\Setup\GravityForms\FieldRenderers;

final class TimeFieldRenderer extends BaseFieldRenderer
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

        // Time format configuration
        $timeFormat = property_exists($field, 'timeFormat') ? $field->timeFormat : '12';
        $is12Hour = $timeFormat === '12';

        $viewModel['timeFormat'] = $timeFormat;
        $viewModel['is12Hour'] = $is12Hour;

        // Parse existing values
        $viewModel['valuesBySuffix'] = $this->parseTimeValues($value, $field->id);

        // Process sub-field labels
        $viewModel['subFieldLabels'] = $this->processSubFieldLabels($field);

        // Generate input attributes
        $viewModel['inputAttributes'] = $this->generateInputAttributes($is12Hour, $viewModel['failed']);

        return $viewModel;
    }

    /**
     * Parse time values from various formats
     */
    private function parseTimeValues($value, int $fieldId): array
    {
        $valuesBySuffix = ['1' => '', '2' => '', '3' => ''];

        if (is_array($value)) {
            // Support both keyed and numeric arrays
            $valuesBySuffix['1'] = (string) ($value['hour'] ?? ($value['1'] ?? ($value[0] ?? '')));
            $valuesBySuffix['2'] = (string) ($value['minute'] ?? ($value['2'] ?? ($value[1] ?? '')));
            $valuesBySuffix['3'] = (string) ($value['ampm'] ?? ($value['3'] ?? ($value[2] ?? '')));
        } elseif (is_string($value) && !empty($value)) {
            // Parse time string like "14:30" or "2:30 PM"
            if (preg_match('/^(\d{1,2}):(\d{2})\s*(AM|PM)?$/i', $value, $matches)) {
                $valuesBySuffix['1'] = $matches[1];
                $valuesBySuffix['2'] = $matches[2];
                $valuesBySuffix['3'] = $matches[3] ?? '';
            }
        }

        // Fallback to POST when available
        foreach (['1','2','3'] as $suf) {
            if ($valuesBySuffix[$suf] === '') {
                $postKey = 'input_' . $fieldId . '_' . $suf;
                $posted = function_exists('rgpost') ? rgpost($postKey) : ($_POST[$postKey] ?? '');
                if (is_string($posted) && $posted !== '') {
                    $valuesBySuffix[$suf] = $posted;
                }
            }
        }

        return $valuesBySuffix;
    }

    /**
     * Process sub-field labels from field inputs
     */
    private function processSubFieldLabels(object $field): array
    {
        // Default labels
        $labels = [
            'hour' => __('Hour', 'folkingebrew'),
            'minute' => __('Minute', 'folkingebrew'),
            'ampm' => __('AM/PM', 'folkingebrew'),
        ];

        if (property_exists($field, 'inputs') && is_array($field->inputs)) {
            foreach ($field->inputs as $input) {
                if (!empty($input) && is_array($input)) {
                    $inputId = $input['id'] ?? '';
                    // Use customLabel if available, otherwise fall back to label
                    $labelToUse = $input['customLabel'] ?? $input['label'] ?? '';

                    if (str_ends_with($inputId, '.1') && !empty($labelToUse)) {
                        $labels['hour'] = $labelToUse;
                    } elseif (str_ends_with($inputId, '.2') && !empty($labelToUse)) {
                        $labels['minute'] = $labelToUse;
                    } elseif (str_ends_with($inputId, '.3') && !empty($labelToUse)) {
                        $labels['ampm'] = $labelToUse;
                    }
                }
            }
        }

        return $labels;
    }

    /**
     * Generate input attributes based on time format
     */
    private function generateInputAttributes(bool $is12Hour, bool $failed): array
    {
        return [
            'hour' => [
                'placeholder' => $is12Hour ? '12' : '00',
                'min' => $is12Hour ? '1' : '0',
                'max' => $is12Hour ? '12' : '23',
                'step' => '1',
            ],
            'minute' => [
                'placeholder' => '00',
                'min' => '0',
                'max' => '59',
                'step' => '1',
            ],
            'classes' => [
                'base' => 'border rounded px-3 py-2 w-20 text-center',
                'error' => $failed ? 'border-red-500' : 'border-gray-300',
            ],
        ];
    }
}
