<?php

declare(strict_types=1);

namespace Custom\Setup\GravityForms\FieldRenderers;

final class MultipleChoiceFieldRenderer extends BaseFieldRenderer
{
    public function supports(string $fieldType): bool
    {
        return in_array($fieldType, ['checkbox', 'radio', 'multi_choice'], true);
    }

    public function getViewName(): string
    {
        return 'gravity.fields.multiplechoice';
    }

    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $viewModel = parent::buildViewModel($field, $value, $formId, $leadId);

        $fieldId         = (int) ($field->id ?? 0);
        $choices         = $this->normalizeChoices($field->choices ?? []);
        $typeInfo        = $this->detectFieldType($field);
        $selectionConfig = $this->buildSelectionConfig($field, $typeInfo['isRadio'], $typeInfo['isCheckbox']);

        // Base input names per GF convention
        $inputNameRadio = "input_{$fieldId}";       // radios share one name
        // checkboxes use dot-per-choice names, e.g. input_5.1, input_5.2 ...

        $viewModel += [
            'fieldId'           => $fieldId,
            'formId'            => $formId,
            'choices'           => $choices,
            'isRadio'           => $typeInfo['isRadio'],
            'isCheckbox'        => $typeInfo['isCheckbox'],
            'inputType'         => $typeInfo['inputType'],
            'actualFieldType'   => $typeInfo['actualFieldType'],
            'selectMode'        => $selectionConfig['selectMode'],
            'exactCount'        => $selectionConfig['exactCount'],
            'minRange'          => $selectionConfig['minRange'],
            'maxRange'          => $selectionConfig['maxRange'],
            'isMultipleSelection' => $selectionConfig['isMultipleSelection'],
            'enableSelectAll'   => $selectionConfig['enableSelectAll'],
            'selectAllText'     => $selectionConfig['selectAllText'],
            'enableOther'       => $selectionConfig['enableOther'],
            'otherText'         => $selectionConfig['otherText'],
            'fieldIdentifier'   => "mc_field_{$formId}_{$fieldId}",
            // convenience for radios
            'inputNameRadio'    => $inputNameRadio,
        ];

        // Process choices with selection state
        $viewModel['processedChoices'] = $this->processChoices(
            $choices,
            $value,
            $fieldId,
            $formId,
            $typeInfo['inputType'],
            $inputNameRadio
        );

        // Process “Other” if enabled
        if ($viewModel['enableOther']) {
            $viewModel['otherChoice'] = $this->processOtherChoice(
                count($choices),
                $value,
                $fieldId,
                $formId,
                $typeInfo['inputType'],
                $inputNameRadio
            );
        }

        // Feature summary for view toggles
        $viewModel['hasAdvancedFeatures'] = $this->hasAdvancedFeatures($viewModel);

        return $viewModel;
    }

    /**
     * Normalize GF choices to a stable array shape.
     *
     * @param mixed $choices
     * @return array<int, array{value:string,text:string,isSelected:bool}>
     */
    private function normalizeChoices($choices): array
    {
        if (!is_array($choices)) {
            return [];
        }

        $out = [];
        foreach (array_values($choices) as $c) {
            if (is_array($c)) {
                $value = (string) ($c['value'] ?? ($c['text'] ?? ''));
                $text  = (string) ($c['text']  ?? $value);
                $out[] = [
                    'value'      => $value,
                    'text'       => $text,
                    'isSelected' => (bool) ($c['isSelected'] ?? false),
                ];
            } elseif (is_object($c)) {
                $val  = isset($c->value) ? (string) $c->value : (isset($c->text) ? (string) $c->text : '');
                $text = isset($c->text) ? (string) $c->text : $val;
                $out[] = [
                    'value'      => $val,
                    'text'       => $text,
                    'isSelected' => (bool) ($c->isSelected ?? false),
                ];
            }
        }

        return $out;
    }

    /**
     * Use concrete GF classes when available, fall back to inputType string.
     */
    private function detectFieldType(object $field): array
    {
        $isRadio    = class_exists('\GF_Field_Radio')    ? ($field instanceof \GF_Field_Radio)    : false;
        $isCheckbox = class_exists('\GF_Field_Checkbox') ? ($field instanceof \GF_Field_Checkbox) : false;

        $inputTypeProperty = (string) ($field->inputType ?? '');
        if (!$isRadio && !$isCheckbox) {
            $isRadio    = $inputTypeProperty === 'radio';
            $isCheckbox = $inputTypeProperty === 'checkbox';
        }

        $inputType = $isRadio ? 'radio' : ($isCheckbox ? 'checkbox' : ($inputTypeProperty ?: 'radio'));

        return [
            'isRadio'         => $isRadio,
            'isCheckbox'      => $isCheckbox,
            'inputType'       => $inputType,
            'actualFieldType' => (string) ($field->type ?? $inputType),
        ];
    }

    /**
     * Choice-limit semantics (kept flexible to tolerate different GF versions).
     */
    private function buildSelectionConfig(object $field, bool $isRadio, bool $isCheckbox): array
    {
        $choiceLimit       = property_exists($field, 'choiceLimit')       ? (string) $field->choiceLimit       : null;
        $choiceLimitNumber = property_exists($field, 'choiceLimitNumber') ? (int) $field->choiceLimitNumber    : 0;
        $choiceLimitMin    = property_exists($field, 'choiceLimitMin')    ? (int) $field->choiceLimitMin       : 0;
        $choiceLimitMax    = property_exists($field, 'choiceLimitMax')    ? (int) $field->choiceLimitMax       : 0;

        // Select mode
        if ($isRadio) {
            $selectMode = 'single';
        } elseif ($choiceLimit === 'exactly' && $choiceLimitNumber > 0) {
            $selectMode = 'exactly';
        } elseif ($choiceLimit === 'range' && ($choiceLimitMin > 0 || $choiceLimitMax > 0)) {
            $selectMode = 'range';
        } else {
            $selectMode = 'multiple';
        }

        // Advanced ranges/defaults
        $exactCount = $selectMode === 'exactly'
            ? $choiceLimitNumber
            : (property_exists($field, 'exactCount') ? (int) $field->exactCount : 1);

        $minRange = $selectMode === 'range'
            ? $choiceLimitMin
            : (property_exists($field, 'minRange') ? (int) $field->minRange : 0);

        $maxRange = $selectMode === 'range'
            ? $choiceLimitMax
            : (property_exists($field, 'maxRange') ? (int) $field->maxRange : 0);

        // Select All / Other
        $enableSelectAll = property_exists($field, 'enableSelectAll') ? (bool) $field->enableSelectAll : $isCheckbox;
        $enableOther     = property_exists($field, 'enableOtherChoice') ? (bool) $field->enableOtherChoice : false;

        // Preserve original behavior: disable for checkbox if that’s your UI decision.
        if ($isCheckbox) {
            $enableOther = false;
        }

        return [
            'selectMode'          => $selectMode,
            'exactCount'          => $exactCount,
            'minRange'            => $minRange,
            'maxRange'            => $maxRange,
            'isMultipleSelection' => in_array($selectMode, ['multiple', 'range', 'exactly'], true),
            'enableSelectAll'     => $enableSelectAll,
            'selectAllText'       => property_exists($field, 'selectAllText') ? (string) $field->selectAllText : __('Select All', 'folkingebrew'),
            'enableOther'         => $enableOther,
            'otherText'           => property_exists($field, 'otherChoiceText') ? (string) $field->otherChoiceText : __('Other', 'folkingebrew'),
        ];
    }

    /**
     * Create per-choice view models with selection state.
     *
     * @param array<int, array{value:string,text:string,isSelected:bool}> $choices
     * @return array<int, array{index:int,value:string,text:string,id:string,name:string,selected:bool,isDefault:bool}>
     */
    private function processChoices(array $choices, $value, int $fieldId, int $formId, string $inputType, string $inputNameRadio): array
    {
        $out = [];
        foreach ($choices as $i => $choice) {
            $suffix   = (string) ($i + 1);
            $domId    = "choice_{$formId}_{$fieldId}_{$suffix}";
            $name     = $inputType === 'radio' ? $inputNameRadio : "input_{$fieldId}.{$suffix}";
            $selected = $this->isChoiceSelected($value, $choice['value'], $suffix, $fieldId, $inputType, $inputNameRadio);

            $out[] = [
                'index'     => $i,
                'value'     => $choice['value'],
                'text'      => $choice['text'],
                'id'        => $domId,
                'name'      => $name,
                'selected'  => $selected,
                'isDefault' => $choice['isSelected'],
            ];
        }

        return $out;
    }

    /**
     * Selection resolution: POST > $value > default.
     */
    private function isChoiceSelected($value, string $choiceValue, string $suffix, int $fieldId, string $inputType, string $inputNameRadio): bool
    {
        // 1) POST
        if ($inputType === 'radio') {
            $posted = $this->rgpostOrPost($inputNameRadio);
            if ($posted !== null) {
                return $posted === $choiceValue;
            }
        } else {
            // Checkbox: GF posts sub-inputs as input_{fieldId}_{suffix}
            $posted = $this->rgpostOrPost("input_{$fieldId}_{$suffix}");
            if ($posted !== null && $posted !== '') {
                return true; // any value means that box was checked
            }
        }

        // 2) Saved $value (GF shapes vary)
        if (is_string($value) && $inputType === 'radio') {
            return $value === $choiceValue;
        }
        if (is_array($value)) {
            // Common shapes:
            // - array of selected values
            // - keyed by suffix ('1' => 'Choice Text')
            // - keyed by 'fieldId.suffix' => 'Choice Text'
            if (in_array($choiceValue, $value, true)) {
                return true;
            }
            if (!empty($value[$suffix])) {
                return true;
            }
            $dotKey = "{$fieldId}.{$suffix}";
            if (!empty($value[$dotKey] ?? null)) {
                return true;
            }
        }

        // 3) No signal; let the template decide to use the original 'isDefault' if desired.
        return false;
    }

    /**
     * Build the "Other" pseudo-choice + text input meta.
     *
     * @return array{suffix:string,choiceId:string,textId:string,subName:string,textName:string,selected:bool,textValue:string,choiceIndex:int}
     */
    private function processOtherChoice(int $choiceCount, $value, int $fieldId, int $formId, string $inputType, string $inputNameRadio): array
    {
        $suffix      = (string) ($choiceCount + 1);
        $choiceId    = "choice_{$formId}_{$fieldId}_other"; // distinct, readable id
        $textId      = "input_{$formId}_{$fieldId}_other";
        $subName     = $inputType === 'radio' ? $inputNameRadio : "input_{$fieldId}.{$suffix}";
        $textName    = "input_{$fieldId}_other"; // GF-style name to carry the free text
        $selected    = false;
        $textValue   = '';

        // POST first
        $postedText = $this->rgpostOrPost($textName);
        if (is_string($postedText) && $postedText !== '') {
            $selected  = true;
            $textValue = $postedText;
        } else {
            if ($inputType === 'radio') {
                $postedRadio = $this->rgpostOrPost($inputNameRadio);
                if (is_string($postedRadio) && $postedRadio === 'gf_other_choice') {
                    $selected = true;
                }
            } else {
                $postedCb = $this->rgpostOrPost("input_{$fieldId}_{$suffix}");
                if (is_string($postedCb) && $postedCb !== '') {
                    $selected = true;
                    // If user typed text, it will live in $textName; leave $textValue as '' if none.
                }
            }
        }

        // Saved $value shapes (best-effort)
        if (!$selected && is_array($value)) {
            $dotKey = "{$fieldId}.{$suffix}";
            if (!empty($value[$dotKey] ?? null) || !empty($value[$suffix] ?? null)) {
                $selected = true;
            }
            if (is_string($value['other'] ?? null) && $value['other'] !== '') {
                $selected  = true;
                $textValue = $value['other'];
            }
        }

        return [
            'suffix'      => $suffix,
            'choiceId'    => $choiceId,
            'textId'      => $textId,
            'subName'     => $subName,
            'textName'    => $textName,
            'selected'    => $selected,
            'textValue'   => $textValue,
            'choiceIndex' => $choiceCount,
        ];
    }

    private function hasAdvancedFeatures(array $config): bool
    {
        return !empty($config['enableSelectAll'])
            || !empty($config['enableOther'])
            || !empty($config['isMultipleSelection'])
            || (isset($config['minRange'], $config['maxRange']) && ($config['minRange'] > 0 || $config['maxRange'] > 0));
    }

    /**
     * Wrapper to prefer rgpost when available.
     *
     * @return string|null
     */
    private function rgpostOrPost(string $key): ?string
    {
        if (function_exists('rgpost')) {
            $v = rgpost($key);
            return is_string($v) ? $v : (is_scalar($v) ? (string) $v : null);
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        if (isset($_POST[$key])) {
            $v = $_POST[$key];
            return is_string($v) ? $v : (is_scalar($v) ? (string) $v : null);
        }

        return null;
    }
}
