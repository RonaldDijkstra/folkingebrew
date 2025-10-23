<?php

namespace App\View\Composers\Blocks;

use Roots\Acorn\View\Composer;

class Content extends Composer
{
    protected static $views = [
        'blocks.content',
    ];

    public function with()
    {
        // Get the raw, unformatted ACF value (avoid auto-shortcodes)
        $rawText = get_field('text', false, false) ?? '';

        $text = $this->processText($rawText);

        return [
            'backgroundColor' => get_field('background_color') ?? 'bg-white',
            'textRight'       => get_field('text_right') ?? false,
            'image'           => get_field('image') ?? '',
            'title'           => get_field('title') ?? '',
            'text'            => $text, // stays HTML-ready
            'link'            => get_field('link') ?? '',
            'contentType'     => get_field('content_type') ?? 'text',
            'openingHours'    => get_field('hours', 'options') ?? [],
            'companyDetails'  => get_field('company_details', 'options') ?? [],
            'directions'      => get_field('directions', 'options') ?? [],
        ];
    }

    private function isPreview(): bool
    {
        // Works for Gutenberg iframe and REST previews
        return is_admin() || (defined('REST_REQUEST') && REST_REQUEST);
    }

    private function processText(string $text): string
    {
        if ($text === '') {
            return $text;
        }

        // Check if it includes a Gravity Form shortcode
        if (preg_match('/\[gravityform[^]]*id="?(\d+)"?[^]]*\]/i', $text, $matches)) {
            $formId = (int) $matches[1];

            if ($this->isPreview()) {
                // Render static preview (no JS, no AJAX)
                return $this->renderGfStaticPreview($formId, $text);
            }

            // Frontend: normal shortcode rendering
            return do_shortcode($text);
        }

        // No Gravity Form shortcode — just render normal text/shortcodes
        return $this->isPreview() ? wpautop($text) : do_shortcode($text);
    }

    private function renderGfStaticPreview(int $formId, string $text): string
    {
        // Check if Gravity Forms API is available
        if (!class_exists('GFAPI')) {
            return wpautop($text);
        }

        // Get the form object
        $form = \GFAPI::get_form($formId);
        if (!$form || is_wp_error($form)) {
            return wpautop($text);
        }

        // Build the form HTML using the blade rendering system
        $formHtml = $this->renderFormWithBlade($form);

        if (!$formHtml) {
            return wpautop($text);
        }

        // Replace the shortcode with our blade-rendered form
        $text = preg_replace('/\[gravityform[^]]*\]/i', $formHtml, $text);

        // Return the HTML without wpautop since form HTML is already complete
        return $text;
    }

    private function renderFormWithBlade(array $form): string
    {
        // Get the field renderer factory
        $fieldRendererFactory = new \Custom\Setup\GravityForms\FieldRenderers\FieldRendererFactory();

        $formId = (int) ($form['id'] ?? 0);
        $fieldsHtml = '';

        // Get form fields
        $fields = $form['fields'] ?? [];

        // Render each field using the blade system
        foreach ($fields as $field) {
            $fieldType = $field->type ?? '';
            $renderer = $fieldRendererFactory->getRenderer($fieldType);

            if ($renderer) {
                // Render the field with empty value for preview
                $fieldHtml = $renderer->render($field, '', $formId, 0);

                // Wrap field in li element to match Gravity Forms structure
                $fieldId = (int) ($field->id ?? 0);
                $fieldClasses = 'gfield gfield_contains_required field_sublabel_below field_description_below gfield_visibility_visible';

                $fieldsHtml .= sprintf(
                    '<li id="field_%d_%d" class="%s" style="list-style: none;">%s</li>',
                    esc_attr($formId),
                    esc_attr($fieldId),
                    esc_attr($fieldClasses),
                    $fieldHtml
                );
            }
        }

        // Wrap the fields in a basic form structure
        $formTitle = $form['title'] ?? '';
        $formDescription = $form['description'] ?? '';

        $formHtml = '<div class="gform_wrapper" id="gform_wrapper_' . esc_attr($formId) . '">';

        if ($formTitle) {
            $formHtml .= '<h3 class="gform_title">' . esc_html($formTitle) . '</h3>';
        }

        if ($formDescription) {
            $formHtml .= '<div class="gform_description">' . wp_kses_post($formDescription) . '</div>';
        }

        $formHtml .= '<form method="post" id="gform_' . esc_attr($formId) . '">';
        $formHtml .= '<div class="gform_body">';
        $formHtml .= '<ul class="w-full list-none !p-0 !m-0 !ms-0 !ps-0 !pl-0">';
        $formHtml .= $fieldsHtml;
        $formHtml .= '</ul>';
        $formHtml .= '</div>';
        $formHtml .= '<div class="gform_footer">';
        $formHtml .= $this->renderSubmitButton($form);
        $formHtml .= '</div>';
        $formHtml .= '</form>';
        $formHtml .= '</div>';

        return $formHtml;
    }

    private function renderSubmitButton(array $form): string
    {
        // Get submit button configuration from the form
        $button = $form['button'] ?? [];
        $buttonText = $button['text'] ?? __('Submit', 'gravityforms');
        $buttonType = $button['type'] ?? 'text';
        $imageUrl = $button['imageUrl'] ?? '';

        // Get button width setting
        $buttonWidth = $button['width'] ?? 'auto';
        $widthClass = match($buttonWidth) {
            'full' => 'w-full',
            'medium' => 'w-1/2',
            'small' => 'w-1/4',
            default => 'w-auto'
        };

        // Build view model for the submit button
        $viewModel = [
            'form' => $form,
            'buttonText' => $buttonText . ' (Preview)',
            'buttonType' => 'text', // Force text type for preview
            'imageUrl' => $imageUrl,
            'containerClass' => 'gform_footer_container',
            'widthClass' => $widthClass,
            'isPreview' => true,
        ];

        return view('gravity.submit', $viewModel)->render();
    }
}
