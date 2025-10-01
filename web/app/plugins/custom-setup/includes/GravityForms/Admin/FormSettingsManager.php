<?php
namespace Custom\Setup\GravityForms\Admin;

class FormSettingsManager
{
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
}
