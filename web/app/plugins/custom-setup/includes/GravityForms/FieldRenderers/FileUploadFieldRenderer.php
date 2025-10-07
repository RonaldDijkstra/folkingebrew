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
        $viewModel['maxFiles'] = property_exists($field, 'maxFiles') ? $field->maxFiles : null;

        // Generate all the IDs and settings
        $viewModel = array_merge($viewModel, $this->generateFileUploadSettings($field, $formId));

        return $viewModel;
    }

    private function generateFileUploadSettings(object $field, int $formId): array
    {
        $fieldId = $field->id;

        // Generate IDs
        $fieldElementId = "field_{$formId}_{$fieldId}";
        $multiWrapId = "gform_multifile_upload_{$formId}_{$fieldId}";
        $dropAreaId = "gform_drag_drop_area_{$formId}_{$fieldId}";
        $browseBtnId = "gform_browse_button_{$formId}_{$fieldId}";
        $rulesId = "gfield_upload_rules_{$formId}_{$fieldId}";
        $messagesId = "gform_multifile_messages_{$formId}_{$fieldId}";
        $previewId = "gform_preview_{$formId}_{$fieldId}";

        // File size settings
        $maxFileSizeMB = !empty($field->maxFileSize) ? (int) $field->maxFileSize : 512;
        $maxFileSizeBytes = $maxFileSizeMB * 1024 * 1024;

        // Allowed extensions
        $allowedRaw = !empty($field->allowedExtensions) ? trim($field->allowedExtensions) : '*';
        $accept = '*';
        if ($allowedRaw !== '*') {
            $accept = implode(',', array_filter(array_map(function ($e) {
                $e = strtolower(trim($e));
                return $e !== '' ? '.' . ltrim($e, '.') : '';
            }, explode(',', $allowedRaw))));
        }

        // Max files (0 = unlimited)
        $maxFiles = !empty($field->maxFiles) ? (int) $field->maxFiles : 0;

        // Upload URL
        $uploadUrl = $this->getUploadUrl();

        // Uploader settings
        $settings = [
            'runtimes' => 'html5,html4',
            'browse_button' => $browseBtnId,
            'container' => $multiWrapId,
            'drop_element' => $dropAreaId,
            'filelist' => $previewId,
            'unique_names' => true,
            'file_data_name' => 'file',
            'url' => $uploadUrl,
            'filters' => [
                'mime_types' => [['title' => 'Allowed Files', 'extensions' => $allowedRaw]],
                'max_file_size' => $maxFileSizeBytes . 'b',
            ],
            'multipart' => true,
            'urlstream_upload' => false,
            'multipart_params' => [
                'form_id' => $formId,
                'field_id' => $fieldId,
            ],
            'gf_vars' => [
                'max_files' => $maxFiles,
                'message_id' => $messagesId,
            ],
        ];

        // Convert settings to JSON
        $settingsJson = json_encode($settings, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES);

        return [
            'fieldId' => $fieldId,
            'formId' => $formId,
            'fieldElementId' => $fieldElementId,
            'multiWrapId' => $multiWrapId,
            'dropAreaId' => $dropAreaId,
            'browseBtnId' => $browseBtnId,
            'rulesId' => $rulesId,
            'messagesId' => $messagesId,
            'previewId' => $previewId,
            'maxFileSizeMB' => $maxFileSizeMB,
            'maxFileSizeBytes' => $maxFileSizeBytes,
            'allowedRaw' => $allowedRaw,
            'accept' => $accept,
            'maxFiles' => $maxFiles,
            'uploadUrl' => $uploadUrl,
            'settings' => $settings,
            'settingsJson' => $settingsJson,
        ];
    }

    /**
     * Get the dynamic upload URL with proper gf_page parameter
     *
     * @return string The upload URL
     */
    private function getUploadUrl(): string
    {
        // Try to get the upload page slug from Gravity Forms
        if (class_exists('GFCommon') && method_exists('GFCommon', 'get_upload_page_slug')) {
            $uploadPageSlug = \GFCommon::get_upload_page_slug();
            return home_url('/wp/?gf_page=' . $uploadPageSlug);
        }

        // Fallback: use the default upload page format
        // This maintains backward compatibility if Gravity Forms methods are not available
        return home_url('/wp/?gf_page=upload');
    }
}