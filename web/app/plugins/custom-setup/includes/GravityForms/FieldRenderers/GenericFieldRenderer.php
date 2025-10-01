<?php

namespace Custom\Setup\GravityForms\FieldRenderers;

class GenericFieldRenderer extends BaseFieldRenderer
{
    private string $viewName;

    public function __construct(string $viewName)
    {
        $this->viewName = $viewName;
    }

    public function supports(string $fieldType): bool
    {
        // This renderer can be used for any field type
        return true;
    }

    public function getViewName(): string
    {
        return $this->viewName;
    }
}
