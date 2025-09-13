<?php

namespace Custom\Setup\GravityForms;

use Custom\Setup\ServiceInterface;

class Settings implements ServiceInterface
{
    public function register()
    {
        // Allow shortcodes in Gravity Forms fields
        add_filter('acf/format_value/type=textarea', 'do_shortcode');
        add_filter('acf/format_value/type=text', 'do_shortcode');
        add_filter('gform_field_content', [$this, 'processGravityFormsHtmlShortcodes'], 10, 5);
        // Disable Gravity Forms styling
        add_filter('gform_disable_css', '__return_true');
        // Disable auto-paragraphs in Gravity Forms fields
        add_filter('gform_enable_wpautop', '__return_false');
        // Custom validation message
        add_filter('gform_validation_message', [$this, 'renderValidationMessage'], 10, 2);
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

    /**
     * Render validation message with a Blade template
     *
     * @param string $validation_message The original validation message HTML
     * @param array $form The form object
     * @return string The rendered validation message HTML
     */
    public function renderValidationMessage($validation_message, $form): string
    {
        if(is_admin()) {
            return $validation_message;
        }

        // Hardly needed, but just in case
        if (function_exists('view') && view()->exists('gravity.validation-message')) {
            return view('gravity.validation-message', [
                'original_message' => $validation_message,
                'form' => $form,
            ])->render();
        }

        return $validation_message;
    }
}
