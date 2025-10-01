<?php
namespace Custom\Setup\GravityForms\Admin;

class DateFieldSettings
{
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
}
