<?php
namespace Custom\Setup\GravityForms\Processors;

class TimeFieldProcessor implements FieldProcessorInterface
{
    /**
     * Process time fields before submission
     * Combines split time field inputs (input_{id}_1/2/3) into input_{id} as H:i or H:i A format
     */
    public function process(array $form): void
    {
        if (empty($form['fields']) || !function_exists('rgpost')) {
            return;
        }

        foreach ($form['fields'] as $field) {
            if (($field->type ?? '') !== 'time') {
                continue;
            }

            $fid = (int) $field->id;
            $hour = rgpost('input_' . $fid . '_1');
            $minute = rgpost('input_' . $fid . '_2');
            $ampm = rgpost('input_' . $fid . '_3');

            // Only process if we have hour and minute values
            if ($hour !== '' && $minute !== '' && $hour !== null && $minute !== null) {
                $timeFormat = $field->timeFormat ?? '12';
                $is12Hour = $timeFormat === '12';

                // Validate and format hour
                $hourInt = (int) $hour;
                $minuteInt = (int) $minute;

                if ($is12Hour) {
                    // For 12-hour format, include AM/PM
                    if ($ampm && ($ampm === 'AM' || $ampm === 'PM')) {
                        $_POST['input_' . $fid] = sprintf('%d:%02d %s', $hourInt, $minuteInt, $ampm);
                    }
                } else {
                    // For 24-hour format, just hour:minute
                    $_POST['input_' . $fid] = sprintf('%02d:%02d', $hourInt, $minuteInt);
                }
            }
        }
    }

    /**
     * Check if this processor supports the given field type
     */
    public function supports(string $fieldType): bool
    {
        return $fieldType === 'time';
    }

    /**
     * Get the processing priority
     */
    public function getPriority(): int
    {
        return 20;
    }
}
