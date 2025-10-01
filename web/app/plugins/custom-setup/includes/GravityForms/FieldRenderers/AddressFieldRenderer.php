<?php

declare(strict_types=1);

namespace Custom\Setup\GravityForms\FieldRenderers;

final class AddressFieldRenderer extends BaseFieldRenderer
{
    /**
     * Gravity Forms address input suffix mapping.
     */
    private const TYPE_MAP = [
        '1' => ['key' => 'street',   'placeholder' => 'Street Address', 'full' => true],
        '2' => ['key' => 'address2', 'placeholder' => 'Address Line 2', 'full' => true],
        '3' => ['key' => 'city',     'placeholder' => 'City', 'full' => false],
        '4' => ['key' => 'state',    'placeholder' => 'State / Province / Region', 'full' => false],
        '5' => ['key' => 'zip',      'placeholder' => 'ZIP / Postal Code', 'full' => false],
        '6' => ['key' => 'country',  'placeholder' => 'Country', 'full' => false],
    ];

    public function supports(string $fieldType): bool
    {
        return $fieldType === 'address';
    }

    public function getViewName(): string
    {
        return 'gravity.fields.address';
    }

    /**
     * @param object     $field  Gravity Forms field (GF_Field_Address)
     * @param mixed      $value  Entry value structure for the field (array of sub-input values or empty)
     * @param int        $formId GF form id
     * @param int        $leadId Entry id
     */
    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $viewModel = parent::buildViewModel($field, $value, $formId, $leadId);

        // Countries (GF_Field_Address::get_countries) when available.
        $countries = method_exists($field, 'get_countries') ? (array) $field->get_countries() : [];
        $viewModel['countries'] = $countries;

        // Merge processed address data.
        return array_merge(
            $viewModel,
            $this->processAddressInputs($field, $value, $viewModel, $formId)
        );
    }

    /**
     * Build per-input data for the view.
     *
     * @param object $field     GF field
     * @param mixed  $value     entry value
     * @param array  $viewModel parent view model (must contain fieldId, optionally ariaDescId/isRequired/failed/message/description)
     * @param int    $formId    GF form id
     */
    private function processAddressInputs(object $field, $value, array $viewModel, int $formId): array
    {
        $rawInputs = isset($field->inputs) && is_array($field->inputs) ? $field->inputs : [];
        $fieldId   = (int) ($viewModel['fieldId'] ?? 0);

        $full = [];
        $half = [];

        foreach ($rawInputs as $input) {
            $normalized = $this->normalizeInput($input);
            if ($normalized === null) {
                continue;
            }

            // Validate id like "123.1" and make sure it belongs to this field.
            [$idFieldId, $suffix] = $this->parseInputId($normalized['id']);
            if ($idFieldId !== $fieldId || !isset(self::TYPE_MAP[$suffix])) {
                continue;
            }

            if ($normalized['isHidden']) {
                continue;
            }

            $meta = self::TYPE_MAP[$suffix];

            $inputData = [
                'id'          => $normalized['id'],                              // e.g. "5.1"
                'domId'       => "input_{$formId}_{$fieldId}_{$suffix}",         // GF convention
                'name'        => "input_{$fieldId}_{$suffix}",                   // GF convention
                'label'       => $normalized['label'],
                'placeholder' => $normalized['placeholder'] ?: $meta['placeholder'],
                'type'        => $meta['key'],                                   // street, city, zip, ...
                'isFull'      => (bool) $meta['full'],
                'value'       => $this->getInputValue($value, $normalized['id']),
            ];

            if ($meta['full']) {
                $full[] = $inputData;
            } else {
                $half[] = $inputData;
            }
        }

        return [
            'full'                  => $full,
            'half'                  => $half,
            'renderCountryAsSelect' => !empty($viewModel['countries']),
            // Pass-throughs with defaults
            'ariaDescId'            => (string) ($viewModel['ariaDescId'] ?? "desc_{$fieldId}"),
            'isRequired'            => (bool)   ($viewModel['isRequired'] ?? false),
            'failed'                => (bool)   ($viewModel['failed'] ?? false),
            'message'               => (string) ($viewModel['message'] ?? ''),
            'description'           => (string) ($viewModel['description'] ?? ''),
        ];
    }

    /**
     * Normalize a GF input object/array to a consistent array.
     *
     * @param mixed $input
     * @return array{id:string,isHidden:bool,label:string,placeholder:string}|null
     */
    private function normalizeInput($input): ?array
    {
        if (is_object($input)) {
            $id          = isset($input->id) ? (string) $input->id : '';
            $isHidden    = (bool) ($input->isHidden ?? false);
            $label       = is_string($input->label ?? null) ? $input->label : '';
            $placeholder = is_string($input->placeholder ?? null) ? $input->placeholder : '';
        } elseif (is_array($input)) {
            $id          = isset($input['id']) ? (string) $input['id'] : '';
            $isHidden    = (bool) ($input['isHidden'] ?? false);
            $label       = is_string($input['label'] ?? null) ? $input['label'] : '';
            $placeholder = is_string($input['placeholder'] ?? null) ? $input['placeholder'] : '';
        } else {
            return null;
        }

        if ($id === '') {
            return null;
        }

        return [
            'id'          => $id,
            'isHidden'    => $isHidden,
            'label'       => $label,
            'placeholder' => $placeholder,
        ];
    }

    /**
     * Parse a sub-input id like "12.3" into [fieldId, suffix].
     *
     * @return array{0:int,1:string}
     */
    private function parseInputId(string $inputId): array
    {
        $parts = explode('.', $inputId, 2);
        if (count($parts) !== 2) {
            return [0, ''];
        }

        return [(int) $parts[0], $parts[1]];
    }

    /**
     * Safely fetch value for a sub-input id from the entry value structure.
     *
     * @param mixed  $value
     * @param string $inputId e.g. "5.1"
     */
    private function getInputValue($value, string $inputId): string
    {
        if (is_array($value)) {
            // Gravity Forms stores entry values under keys equal to the sub-input id ("5.1", "5.2", ...)
            $v = $value[$inputId] ?? '';

            return is_scalar($v) ? (string) $v : '';
        }

        return '';
    }
}
