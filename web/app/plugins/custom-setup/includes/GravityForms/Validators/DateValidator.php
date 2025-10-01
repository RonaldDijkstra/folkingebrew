<?php
namespace Custom\Setup\GravityForms\Validators;

class DateValidator implements FieldValidatorInterface
{
    /**
     * Validate a date field value
     */
    public function validate(array $result, $value, array $form, object $field): array
    {
        // Only validate date fields with noPastDates setting enabled
        if ($field->type !== 'date' || empty($field->noPastDates)) {
            return $result;
        }

        // Parse the submitted date value (supports datepicker and datefield)
        $submitted_date = $this->parseSubmittedDate($value, $field);

        if ($submitted_date) {
            $today = new \DateTime('today'); // Today at 00:00:00

            // Check if the submitted date is in the past
            if ($submitted_date < $today) {
                $result['is_valid'] = false;
                $result['message'] = __('Past dates are not allowed. Please select today or a future date.', 'folkingebrew');
            }
        }

        return $result;
    }

    /**
     * Check if this validator supports the given field type
     */
    public function supports(string $fieldType): bool
    {
        return $fieldType === 'date';
    }

    /**
     * Get the validation priority
     */
    public function getPriority(): int
    {
        return 10;
    }

    /**
     * Parse the submitted date value into a DateTime object
     * Handles string values, associative arrays with year/month/day, numeric suffix arrays (1/2/3),
     * and falls back to rgpost using the field id for datefield inputs.
     *
     * @param mixed $value The submitted date value
     * @param object|null $field The current field (optional for fallbacks)
     * @return \DateTime|null The parsed date or null if invalid
     */
    private function parseSubmittedDate($value, $field = null)
    {
        if (is_string($value)) {
            // Handle string date input (e.g., "2024-01-15" or "01/15/2024")
            try {
                return new \DateTime($value);
            } catch (\Exception $e) {
                // continue to other strategies
            }
        }

        if (is_array($value)) {
            // Case 1: ['year' => '2024', 'month' => '01', 'day' => '15']
            if (isset($value['year'], $value['month'], $value['day'])) {
                try {
                    $date_string = sprintf('%04d-%02d-%02d', $value['year'], $value['month'], $value['day']);
                    return new \DateTime($date_string);
                } catch (\Exception $e) {
                    // fall through
                }
            }

            // Case 2: Numeric suffix keys: ['1' => '01', '2' => '15', '3' => '2024'] where 1=month,2=day,3=year
            if (isset($value['1'], $value['2'], $value['3'])) {
                try {
                    $date_string = sprintf('%04d-%02d-%02d', $value['3'], $value['1'], $value['2']);
                    return new \DateTime($date_string);
                } catch (\Exception $e) {
                    // fall through
                }
            }

            // Case 3: Keys like '<fieldId>.1', '<fieldId>.2', '<fieldId>.3'
            $month = $day = $year = null;
            foreach ($value as $k => $v) {
                if (!is_string($k)) continue;
                if (preg_match('/\.([123])$/', $k, $m)) {
                    if ($m[1] === '1') $month = $v;
                    if ($m[1] === '2') $day = $v;
                    if ($m[1] === '3') $year = $v;
                }
            }
            if ($year !== null && $month !== null && $day !== null) {
                try {
                    $date_string = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    return new \DateTime($date_string);
                } catch (\Exception $e) {
                    // fall through
                }
            }
        }

        // Case 4: Fallback to POST using field id (only if field available)
        if ($field && function_exists('rgpost')) {
            $fid = (int) $field->id;
            $m = rgpost('input_' . $fid . '_1'); // Month
            $d = rgpost('input_' . $fid . '_2'); // Day
            $y = rgpost('input_' . $fid . '_3'); // Year
            if ($y !== null && $m !== null && $d !== null && $y !== '' && $m !== '' && $d !== '') {
                try {
                    $date_string = sprintf('%04d-%02d-%02d', (int) $y, (int) $m, (int) $d);
                    return new \DateTime($date_string);
                } catch (\Exception $e) {
                    // ignore
                }
            }
        }

        return null;
    }

    /**
     * Ensure datefield gets failed_validation state when needed, even if field-level hook missed it
     * This is called during the gform_validation filter
     *
     * @param array $validation_result The validation result
     * @return array The validation result
     */
    public function enforceNoPastDatesOnDatefields(array $validation_result): array
    {
        if (!is_array($validation_result) || empty($validation_result['form']['fields'])) {
            return $validation_result;
        }

        $form =& $validation_result['form'];
        $form_is_valid = $validation_result['is_valid'];

        foreach ($form['fields'] as &$field) {
            if (($field->type ?? '') !== 'date') {
                continue;
            }
            if (empty($field->noPastDates)) {
                continue;
            }

            // Attempt to parse from POST for split datefields
            $submitted_date = $this->parseSubmittedDate(null, $field);
            if (!$submitted_date instanceof \DateTime) {
                continue;
            }

            $today = new \DateTime('today');
            if ($submitted_date < $today) {
                $field->failed_validation = true;
                $field->validation_message = __('Past dates are not allowed. Please select today or a future date.', 'folkingebrew');
                $form_is_valid = false;
            }
        }
        unset($field);

        $validation_result['form'] = $form;
        $validation_result['is_valid'] = $form_is_valid;

        return $validation_result;
    }
}
