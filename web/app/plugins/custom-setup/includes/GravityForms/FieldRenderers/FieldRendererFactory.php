<?php

namespace Custom\Setup\GravityForms\FieldRenderers;

class FieldRendererFactory
{
    private array $renderers = [];

    public function __construct()
    {
        $this->registerDefaultRenderers();
    }

    /**
     * Register the default field renderers
     */
    private function registerDefaultRenderers(): void
    {
        $this->registerRenderer('text', new TextFieldRenderer());
        $this->registerRenderer('email', new TextFieldRenderer());
        $this->registerRenderer('number', new TextFieldRenderer());
        $this->registerRenderer('website', new TextFieldRenderer());
        $this->registerRenderer('phone', new TextFieldRenderer());
        $this->registerRenderer('checkbox', new MultipleChoiceFieldRenderer());
        $this->registerRenderer('radio', new MultipleChoiceFieldRenderer());
        $this->registerRenderer('multi_choice', new MultipleChoiceFieldRenderer());
        $this->registerRenderer('date', new DateFieldRenderer());
        $this->registerRenderer('time', new TimeFieldRenderer());
        $this->registerRenderer('consent', new ConsentFieldRenderer());
        $this->registerRenderer('address', new AddressFieldRenderer());
        $this->registerRenderer('fileupload', new FileUploadFieldRenderer());
        $this->registerRenderer('list', new ListFieldRenderer());
        $this->registerRenderer('html', new HtmlFieldRenderer());
        $this->registerRenderer('textarea', new GenericFieldRenderer('gravity.fields.textarea'));
        $this->registerRenderer('select', new GenericFieldRenderer('gravity.fields.select'));
        $this->registerRenderer('section', new GenericFieldRenderer('gravity.section'));
        $this->registerRenderer('name', new GenericFieldRenderer('gravity.fields.name'));
    }

    /**
     * Register a field renderer for a specific field type
     *
     * @param string $fieldType The field type
     * @param FieldRendererInterface $renderer The renderer instance
     */
    public function registerRenderer(string $fieldType, FieldRendererInterface $renderer): void
    {
        $this->renderers[$fieldType] = $renderer;
    }

    /**
     * Get a renderer for the given field type
     *
     * @param string $fieldType The field type
     * @return FieldRendererInterface|null The renderer or null if not found
     */
    public function getRenderer(string $fieldType): ?FieldRendererInterface
    {
        return $this->renderers[$fieldType] ?? null;
    }

    /**
     * Check if a renderer exists for the given field type
     *
     * @param string $fieldType The field type
     * @return bool True if a renderer exists
     */
    public function hasRenderer(string $fieldType): bool
    {
        return isset($this->renderers[$fieldType]);
    }

    /**
     * Get all registered field types
     *
     * @return array Array of field types
     */
    public function getSupportedFieldTypes(): array
    {
        return array_keys($this->renderers);
    }
}