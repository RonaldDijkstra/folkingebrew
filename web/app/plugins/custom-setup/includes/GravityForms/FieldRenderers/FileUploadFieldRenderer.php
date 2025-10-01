<?php

namespace Custom\Setup\GravityForms\FieldRenderers;

class FileUploadFieldRenderer extends BaseFieldRenderer
{
    public function supports(string $fieldType): bool
    {
        return $fieldType === 'fileupload';
    }

    public function getViewName(): string
    {
        return 'gravity.fields.fileupload';
    }

    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $viewModel = parent::buildViewModel($field, $value, $formId, $leadId);

        // File upload specific properties
        $viewModel['maxFileSize'] = property_exists($field, 'maxFileSize') ? $field->maxFileSize : null;
        $viewModel['allowedExtensions'] = property_exists($field, 'allowedExtensions') ? $field->allowedExtensions : null;
        $viewModel['multipleFiles'] = property_exists($field, 'multipleFiles') ? (bool) $field->multipleFiles : false;

        return $viewModel;
    }
}
