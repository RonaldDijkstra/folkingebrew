<?php

declare(strict_types=1);

namespace Custom\Setup\GravityForms\FieldRenderers;

final class DateFieldRenderer extends BaseFieldRenderer
{
    private const TEXTDOMAIN = 'folkingebrew';

    /** GF sub-input suffixes */
    private const SUF_MONTH = '1';
    private const SUF_DAY   = '2';
    private const SUF_YEAR  = '3';

    /** Map format key to order chars */
    private const FORMAT_TO_ORDER = [
        'mdy' => ['m','d','y'],
        'dmy' => ['d','m','y'],
        'ymd' => ['y','m','d'],
    ];

    /** Part meta */
    private const PART_META = [
        'm' => ['suffix' => self::SUF_MONTH, 'fallback' => 'Month', 'maxlength' => 2, 'pattern' => '\\d{1,2}'],
        'd' => ['suffix' => self::SUF_DAY,   'fallback' => 'Day',   'maxlength' => 2, 'pattern' => '\\d{1,2}'],
        'y' => ['suffix' => self::SUF_YEAR,  'fallback' => 'Year',  'maxlength' => 4, 'pattern' => '\\d{4}'],
    ];

    public function supports(string $fieldType): bool
    {
        return $fieldType === 'date';
    }

    public function getViewName(): string
    {
        return 'gravity.fields.date';
    }

    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $viewModel = parent::buildViewModel($field, $value, $formId, $leadId);

        // Custom setting
        $viewModel['noPastDates'] = (bool) (property_exists($field, 'noPastDates') ? $field->noPastDates : false);
        if ($viewModel['noPastDates']) {
            // Provide ISO yyyy-mm-dd for <input type="date"> min attr or client logic in your view
            $viewModel['minDate'] = date('Y-m-d');
        }

        return array_merge($viewModel, $this->processDateFieldData($field, $value, $formId));
    }

    /**
     * Build all data needed by both a single date input template and a dropdown template.
     */
    private function processDateFieldData(object $field, $value, int $formId): array
    {
        $fieldId = (int) ($field->id ?? 0);
        $inputs  = $this->normalizeInputs($field->inputs ?? []);

        // labels/placeholders by suffix ('.1' = month, '.2' = day, '.3' = year)
        [$labelsBySuffix, $placeholdersBySuffix] = $this->extractLabelsAndPlaceholders($inputs);

        // mdy, dmy, ymd (strip any suffix like _dash/_slash)
        $order = $this->inferOrder((string) ($field->dateFormat ?? 'mdy'));

        // current values
        $valuesBySuffix = $this->hydrateValues($value, $fieldId);

        // Build processed inputs for the view
        $processedInputs = [];
        foreach ($order as $part) {
            $meta      = self::PART_META[$part];
            $suffix    = $meta['suffix'];
            $domId     = "input_{$formId}_{$fieldId}_{$suffix}";     // GF convention
            $name      = "input_{$fieldId}.{$suffix}";               // GF convention (dot!)

            $fallback  = __($meta['fallback'], self::TEXTDOMAIN);
            $label     = trim($labelsBySuffix[$suffix] ?: $fallback);
            $ph        = trim($placeholdersBySuffix[$suffix] ?: $label ?: $fallback);
            $current   = (string) ($valuesBySuffix[$suffix] ?? '');

            $processedInputs[] = [
                'part'            => $part,
                'suffix'          => $suffix,
                'subId'           => $domId,
                'subName'         => $name,
                'labelText'       => $label,
                'placeholderText' => $ph,
                'current'         => $current,
                'maxlength'       => $meta['maxlength'],
                'pattern'         => $meta['pattern'],
            ];
        }

        // Dropdown helpers
        $months = range(1, 12);
        $years  = range((int) date('Y'), (int) date('Y') + 10);
        $daysMax = 31; // keep generic, server validates actual date

        return [
            'processedInputs'     => $processedInputs,
            'months'              => $months,
            'years'               => $years,
            'daysMax'             => $daysMax,
            'labelsBySuffix'      => $labelsBySuffix,
            'placeholdersBySuffix'=> $placeholdersBySuffix,
            'valuesBySuffix'      => $valuesBySuffix,
            'order'               => $order,
        ];
    }

    /**
     * Normalize GF sub-inputs to arrays with consistent keys.
     *
     * @param mixed $inputs
     * @return array<int, array{id:string,label:string,customLabel:string,placeholder:string,placeholderValue:string}>
     */
    private function normalizeInputs($inputs): array
    {
        if (!is_array($inputs)) {
            return [];
        }

        $out = [];
        foreach ($inputs as $input) {
            if (is_object($input)) {
                $out[] = [
                    'id'               => isset($input->id) ? (string) $input->id : '',
                    'label'            => is_string($input->label ?? null) ? $input->label : '',
                    'customLabel'      => is_string($input->customLabel ?? null) ? $input->customLabel : '',
                    'placeholder'      => is_string($input->placeholder ?? null) ? $input->placeholder : '',
                    'placeholderValue' => is_string($input->placeholderValue ?? null) ? $input->placeholderValue : '',
                ];
            } elseif (is_array($input)) {
                $out[] = [
                    'id'               => (string) ($input['id'] ?? ''),
                    'label'            => (string) ($input['label'] ?? ''),
                    'customLabel'      => (string) ($input['customLabel'] ?? ''),
                    'placeholder'      => (string) ($input['placeholder'] ?? ''),
                    'placeholderValue' => (string) ($input['placeholderValue'] ?? ''),
                ];
            }
        }

        return $out;
    }

    /**
     * @param array<int, array{id:string,label:string,customLabel:string,placeholder:string,placeholderValue:string}> $inputs
     * @return array{0: array{'1':string,'2':string,'3':string}, 1: array{'1':string,'2':string,'3':string}}
     */
    private function extractLabelsAndPlaceholders(array $inputs): array
    {
        $labels = [self::SUF_MONTH => '', self::SUF_DAY => '', self::SUF_YEAR => ''];
        $phs    = [self::SUF_MONTH => '', self::SUF_DAY => '', self::SUF_YEAR => ''];

        foreach ($inputs as $input) {
            $parts  = explode('.', $input['id'] ?? '', 2);
            $suffix = $parts[1] ?? '';
            if (!isset($labels[$suffix])) {
                continue;
            }

            $label = $input['customLabel'] !== '' ? $input['customLabel'] : $input['label'];
            $labels[$suffix] = (string) $label;

            // Gravity Forms may store placeholder under 'placeholder' or 'placeholderValue'
            $ph = $input['placeholder'] !== '' ? $input['placeholder'] : $input['placeholderValue'];
            $phs[$suffix] = (string) ($ph !== '' ? $ph : $label);
        }

        return [$labels, $phs];
    }

    /**
     * Accepts formats like 'mdy', 'dmy', 'ymd', or with suffixes like 'mdy_dash', 'dmy_slash', etc.
     * @return array<int,string> order of parts e.g. ['m','d','y']
     */
    private function inferOrder(string $format): array
    {
        $base = explode('_', $format, 2)[0];
        return self::FORMAT_TO_ORDER[$base] ?? self::FORMAT_TO_ORDER['mdy'];
        // defaults to mdy if unknown
    }

    /**
     * Hydrate values in ['1'=>month,'2'=>day,'3'=>year] with fallbacks to rgpost/$_POST.
     *
     * @param mixed $value
     * @return array{'1':string,'2':string,'3':string}
     */
    private function hydrateValues($value, int $fieldId): array
    {
        $values = [
            self::SUF_MONTH => '',
            self::SUF_DAY   => '',
            self::SUF_YEAR  => '',
        ];

        if (is_array($value)) {
            // Gravity Forms can give either '1','2','3' keys or 'month','day','year'
            $values[self::SUF_MONTH] = (string) ($value['month'] ?? ($value[self::SUF_MONTH] ?? ''));
            $values[self::SUF_DAY]   = (string) ($value['day']   ?? ($value[self::SUF_DAY]   ?? ''));
            $values[self::SUF_YEAR]  = (string) ($value['year']  ?? ($value[self::SUF_YEAR]  ?? ''));
        }

        // Fallback to POST
        foreach ([self::SUF_MONTH, self::SUF_DAY, self::SUF_YEAR] as $suf) {
            if ($values[$suf] !== '') {
                continue;
            }
            $postKey = "input_{$fieldId}_{$suf}";
            $posted  = function_exists('rgpost') ? rgpost($postKey) : ($_POST[$postKey] ?? '');
            if (is_string($posted) && $posted !== '') {
                $values[$suf] = $posted;
            }
        }

        return $values;
    }
}
