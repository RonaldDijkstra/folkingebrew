<?php

namespace Custom\Setup\GravityForms\Settings;

class SettingsManager
{
    /**
     * Register all Gravity Forms settings and configurations
     */
    public function register(): void
    {
        // Allow shortcodes in Gravity Forms fields
        add_filter('acf/format_value/type=textarea', 'do_shortcode');
        add_filter('acf/format_value/type=text', 'do_shortcode');
        add_filter('gform_field_content', [$this, 'processGravityFormsHtmlShortcodes'], 10, 5);

        // Disable Gravity Forms styling
        add_filter('gform_disable_css', '__return_true');

        // Disable auto-paragraphs in Gravity Forms fields
        add_filter('gform_enable_wpautop', '__return_false');

        add_filter('gform_disable_form_theme_css', '__return_true');
    }

    /**
     * Process Gravity Forms HTML Shortcodes
     *
     * @param string $content The HTML content of the field.
     * @param object $field The field object, containing field properties.
     * @param mixed $value The current field value (unused here).
     * @param int $lead_id The ID of the lead entry (unused here).
     * @param int $form_id The ID of the form being processed.
     *
     * @return string Processed HTML content with shortcodes rendered.
     */
    public function processGravityFormsHtmlShortcodes($content, $field, $value, $lead_id, $form_id)
    {
        if ($field->type === 'html') {
            $content = do_shortcode($content);
        }

        return $content;
    }
}
