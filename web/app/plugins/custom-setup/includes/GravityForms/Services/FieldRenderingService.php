<?php
namespace Custom\Setup\GravityForms\Services;

use Custom\Setup\GravityForms\FieldRenderers\FieldRendererFactory;

class FieldRenderingService
{
    private FieldRendererFactory $fieldRendererFactory;

    public function __construct()
    {
        $this->fieldRendererFactory = new FieldRendererFactory();
    }

    /**
     * Register field rendering hooks
     */
    public function register(): void
    {
        add_filter('gform_field_content', [$this, 'renderField'], 10, 5);
    }

    /**
     * Render a field using the appropriate field renderer
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
        $renderer = $this->fieldRendererFactory->getRenderer($type);

        if (!$renderer) {
            // If no renderer, keep GF original content
            return $content;
        }

        // 3) Use the renderer to build and render the field
        return $renderer->render($field, $value, $formId, $leadId);
    }


    /**
     * Extract id, class, and data-* attributes from GF's original wrapper
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
}
