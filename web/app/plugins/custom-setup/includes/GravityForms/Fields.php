<?php
// app/Providers/ThemeServiceProvider.php
namespace Custom\Setup\GravityForms;

use Custom\Setup\ServiceInterface;

class Fields implements ServiceInterface
{
    public function register()
    {
        add_filter('gform_field_content', [$this, 'renderField'], 10, 5);
        add_filter('gform_submit_button', [$this, 'renderSubmitButton'], 10, 2); // @TODO Move this to a better place

        // Add custom field settings for date fields
        add_action('gform_field_standard_settings', [$this, 'addDateFieldSettings'], 10, 2);
        add_filter('gform_tooltips', [$this, 'addDateFieldTooltips']);
        add_action('gform_editor_js', [$this, 'editorScript']);

        // Add validation for no past dates setting
        add_filter('gform_field_validation', [$this, 'validateNoPastDates'], 10, 4);

        // Final pass to ensure failed_validation is set for datefields
        add_filter('gform_validation', [$this, 'enforceNoPastDatesOnDatefields'], 10, 1);

        // Normalize split datefield inputs into a single value for saving
        add_action('gform_pre_submission', [$this, 'normalizeDatefieldSubmission'], 10, 1);

        // Normalize split time field inputs into a single value for saving
        add_action('gform_pre_submission', [$this, 'normalizeTimeFieldSubmission'], 10, 1);

        // Normalize address field country codes to country names
        add_action('gform_pre_submission', [$this, 'normalizeAddressFieldSubmission'], 10, 1);


        add_filter('gform_confirmation', [$this, 'renderConfirmation'], 10, 4);

        // Hide progress bar style setting
        add_action('gform_editor_js', [$this, 'hideProgressBarStyleSetting']);
    }

    /**
     * Ensure datefield gets failed_validation state when needed, even if field-level hook missed it
     *
     * @param array $validation_result The validation result
     * @return array The validation result
     */
    public function enforceNoPastDatesOnDatefields($validation_result): array
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

    /**
     * Render the submit button with a Blade view
     *
     * @param string $button The original submit button HTML
     * @param array $form The form object
     * @return string The rendered submit button HTML
     */
    public function renderSubmitButton($button, $form): string
    {
        if(is_admin()) {
            return $button;
        }

        // Extract submit button settings from the form object
        $submitSettings = [
            'inputType' => $form['button']['type'] ?? 'text',
            'text' => $form['button']['text'] ?? 'Submit',
            'imageUrl' => $form['button']['imageUrl'] ?? '',
            'width' => $form['button']['width'] ?? 'auto',
            'location' => $form['button']['location'] ?? 'bottom',
            'id' => $form['button']['id'] ?? '',
            'conditionalLogic' => $form['button']['conditionalLogic'] ?? null,
        ];

        // Process button settings to determine CSS classes and display logic
        $buttonType = $submitSettings['inputType'];
        $buttonText = $submitSettings['text'];
        $buttonWidth = $submitSettings['width'];
        $buttonLocation = $submitSettings['location'];
        $imageUrl = $submitSettings['imageUrl'];

        // Determine CSS classes based on width setting
        $widthClass = $buttonWidth === 'full' ? 'w-full' : 'w-auto';

        // Determine container classes based on location
        $containerClass = $buttonLocation === 'left' ? 'text-left' :
                         ($buttonLocation === 'center' ? 'text-center' :
                         ($buttonLocation === 'right' ? 'text-right' : ''));

        return view('gravity.submit', [
            'button' => $button,
            'form' => $form,
            'settings' => $submitSettings,
            'buttonType' => $buttonType,
            'buttonText' => $buttonText,
            'buttonWidth' => $buttonWidth,
            'buttonLocation' => $buttonLocation,
            'imageUrl' => $imageUrl,
            'widthClass' => $widthClass,
            'containerClass' => $containerClass,
        ])->render();
    }

    /**
     * Replace the entire field wrapper + content with a Blade view
     *
     * @param string $content The original field content
     * @param object $field The field object
     * @param mixed $value The field value
     * @param int $leadId The lead ID
     * @param int $formId The form ID
     * @return string The rendered field HTML
     */
    public function renderField($content, $field, $value, $leadId, $formId): string
    {
        if(is_admin()) {
            return $content;
        }

        // 1) Reuse GF's wrapper attributes from the generated $content
        [$wrapperId, $wrapperClass, $dataAttrs] = $this->extractWrapperAttrs($content);

        // Get field type first
        $type = $field->type ?? '';

        // 2) Use the field renderer factory
        $factory = new \Custom\Setup\GravityForms\FieldRenderers\FieldRendererFactory();
        $renderer = $factory->getRenderer($type);

        if (!$renderer) {
            // If no renderer, keep GF original content
            return $content;
        }

        // 3) Use the renderer to build and render the field
        return $renderer->render($field, $value, $formId, $leadId);
    }

    /**
     * Extract id, class, and data-* attributes from GF’s original wrapper
     * Keeps conditional logic and JS hooks intact
     *
     * @param string $content The original field content
     * @return array The extracted id, class and data-* attributes
     */
    private function extractWrapperAttrs(string $content): array
    {
        $id = '';
        $class = '';
        $data = '';

        if (preg_match('#<div\s+([^>]+)>#i', $content, $m)) {
            $attrs = $m[1];

            if (preg_match('#id=("|\')([^"\']+)\1#i', $attrs, $mm)) {
                $id = $mm[2];
            }
            if (preg_match('#class=("|\')([^"\']+)\1#i', $attrs, $mm)) {
                $class = $mm[2];
            }

            // Collect all data-* attributes as a raw string
            if (preg_match_all('#\s(data-[a-z0-9\-_]+)=("|\')(.*?)\2#i', $attrs, $all, PREG_SET_ORDER)) {
                foreach ($all as $attr) {
                    $data .= ' ' . $attr[1] . '=' . $attr[2] . $attr[3] . $attr[2];
                }
            }
        }

        return [$id, $class, $data];
    }

    /**
     * Add custom settings for date fields
     *
     * @param int $position The position of the setting
     * @param int $form_id The current form ID
     * @return void
     */
    public function addDateFieldSettings($position, $form_id)
    {
        // Add the setting at position -1 (in the Rules section)
        if ($position == -1) {
            ?>
            <li class="no_past_dates_setting field_setting" style="display:none;">
                <input type="checkbox" id="field_no_past_dates" onclick="SetFieldProperty('noPastDates', this.checked);" />
                <label for="field_no_past_dates" class="inline">
                    <?php esc_html_e('No past dates allowed', 'folkingebrew'); ?>
                    <?php gform_tooltip('form_field_no_past_dates'); ?>
                </label>
            </li>
            <?php
        }
    }

    /**
     * Add tooltip for the custom date field setting
     *
     * @param array $tooltips Existing tooltips
     * @return array Modified tooltips array
     */
    public function addDateFieldTooltips($tooltips)
    {
        $tooltips['form_field_no_past_dates'] = '<h6>' . esc_html__('No Past Dates', 'folkingebrew') . '</h6>' .
                                                esc_html__('Check this option to prevent users from selecting dates in the past. Only current and future dates will be allowed.', 'folkingebrew');
        return $tooltips;
    }

    /**
     * Add JavaScript to handle the custom field setting in the form editor
     *
     * @return void
     */
    public function editorScript()
    {
        ?>
        <script type='text/javascript'>
            // Add the setting to the date field
            fieldSettings.date += ', .no_past_dates_setting';

            // Populate the setting when a field is selected
            jQuery(document).bind('gform_load_field_settings', function(event, field, form) {
                jQuery('#field_no_past_dates').prop('checked', field.noPastDates == true);
            });
        </script>
        <?php
    }

    /**
     * Hide the progress bar style setting from form settings
     *
     * @return void
     */
    public function hideProgressBarStyleSetting()
    {
        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function($) {
                // Hide the progress bar style setting using the correct class
                $('.percentage_style_setting').hide();

                // Also hide by looking for the specific setting in form settings
                $('tr').has('.percentage_style_setting').hide();
                $('.gform-settings-field').has('.percentage_style_setting').hide();
            });
        </script>
        <style>
            /* Hide progress bar style setting with CSS */
            .percentage_style_setting,
            tr:has(.percentage_style_setting),
            .gform-settings-field:has(.percentage_style_setting) {
                display: none !important;
            }
        </style>
        <?php
    }

    /**
     * Validate that no past dates are selected when the setting is enabled
     *
     * @param array $result The validation result
     * @param mixed $value The field value
     * @param array $form The current form
     * @param object $field The current field
     * @return array The validation result
     */
    public function validateNoPastDates($result, $value, $form, $field)
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
     * Combine split datefield inputs (input_{id}_1/2/3) into input_{id} as Y-m-d
     */
    public function normalizeDatefieldSubmission($form)
    {
        if (empty($form['fields']) || !function_exists('rgpost')) {
            return;
        }

        foreach ($form['fields'] as $field) {
            if (($field->type ?? '') !== 'date') {
                continue;
            }

            // Only apply to split datefields (datefield or datedropdown)
            if (!isset($field->dateType) || !in_array($field->dateType, ['datefield', 'datedropdown'], true)) {
                continue;
            }

            $fid = (int) $field->id;
            $m = rgpost('input_' . $fid . '_1');
            $d = rgpost('input_' . $fid . '_2');
            $y = rgpost('input_' . $fid . '_3');

            if ($y !== '' && $m !== '' && $d !== '' && $y !== null && $m !== null && $d !== null) {
                // Zero-pad and assemble
                $yy = (int) $y;
                $mm = str_pad((string) (int) $m, 2, '0', STR_PAD_LEFT);
                $dd = str_pad((string) (int) $d, 2, '0', STR_PAD_LEFT);
                $_POST['input_' . $fid] = sprintf('%04d-%s-%s', $yy, $mm, $dd);
            }
        }
    }

    /**
     * Combine split time field inputs (input_{id}_1/2/3) into input_{id} as H:i or H:i A format
     */
    public function normalizeTimeFieldSubmission($form)
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
     * Normalize address field country codes to country names for consistency
     */
    public function normalizeAddressFieldSubmission($form)
    {
        if (empty($form['fields']) || !function_exists('rgpost')) {
            return;
        }

        foreach ($form['fields'] as $field) {
            if (($field->type ?? '') !== 'address') {
                continue;
            }

            // Get the country list for this field
            $countries = $field->get_countries();
            if (empty($countries) || !is_array($countries)) {
                continue;
            }

            // Check each address sub-input for country field (suffix 6)
            $fid = (int) $field->id;
            $countryInputName = 'input_' . $fid . '_6';
            $countryValue = rgpost($countryInputName);

            if ($countryValue !== null && $countryValue !== '') {
                // If the value is a country code, convert it to the country name
                if (isset($countries[$countryValue])) {
                    $_POST[$countryInputName] = $countries[$countryValue];
                }
                // If it's already a country name, leave it as is
            }
        }
    }


    public function renderConfirmation($confirmation, $form, $entry): string
    {
        // Extract just the text from confirmation
        $confirmationText = is_string($confirmation)
            ? wp_strip_all_tags($confirmation)
            : ($confirmation['message'] ?? $confirmation);

        return view('gravity.confirmation', [
            'confirmation' => $confirmation,
            'confirmationText' => $confirmationText,
            'form' => $form,
            'entry' => $entry,
        ])->render();
    }
}
