<?php
// /Users/ronalddijkstra/Sites/folkingebrew/web/app/plugins/custom-setup/includes/GravityForms/FieldRenderers/MultipleChoiceFieldRenderer.php
namespace Custom\Setup\GravityForms\FieldRenderers;

class MultipleChoiceFieldRenderer extends BaseFieldRenderer
{
    public function supports(string $fieldType): bool
    {
        return in_array($fieldType, ['checkbox', 'radio', 'multi_choice']);
    }

    public function getViewName(): string
    {
        return 'gravity.fields.multiplechoice';
    }

    protected function buildViewModel(object $field, $value, int $formId, int $leadId): array
    {
        $viewModel = parent::buildViewModel($field, $value, $formId, $leadId);

        // Determine field type from the actual Gravity Forms field object
        $actualFieldType = $field->type ?? 'radio';
        $fieldClass = get_class($field);
        $inputTypeProperty = $field->inputType ?? 'radio';

        // Use both field class and inputType property for accurate detection
        $isRadio = ($fieldClass === 'GF_Field_Radio' || $inputTypeProperty === 'radio');
        $isCheckbox = ($fieldClass === 'GF_Field_Checkbox' || $inputTypeProperty === 'checkbox');

        // Selection mode configuration - check for Gravity Forms choice limit properties
        $choiceLimit = property_exists($field, 'choiceLimit') ? $field->choiceLimit : null;
        $choiceLimitNumber = property_exists($field, 'choiceLimitNumber') ? (int) $field->choiceLimitNumber : 0;
        $choiceLimitMin = property_exists($field, 'choiceLimitMin') ? (int) $field->choiceLimitMin : 0;
        $choiceLimitMax = property_exists($field, 'choiceLimitMax') ? (int) $field->choiceLimitMax : 0;

        // Determine select mode based on Gravity Forms properties
        if ($isRadio) {
            $selectMode = 'single';
        } elseif ($choiceLimit === 'exactly' && $choiceLimitNumber > 0) {
            $selectMode = 'exactly';
        } elseif ($choiceLimit === 'range' && ($choiceLimitMin > 0 || $choiceLimitMax > 0)) {
            $selectMode = 'range';
        } else {
            $selectMode = 'multiple';
        }

        // Advanced feature settings
        $exactCount = ($selectMode === 'exactly') ? $choiceLimitNumber :
                    (property_exists($field, 'exactCount') ? (int) $field->exactCount : 1);
        $minRange = ($selectMode === 'range') ? $choiceLimitMin :
                  (property_exists($field, 'minRange') ? (int) $field->minRange : 0);
        $maxRange = ($selectMode === 'range') ? $choiceLimitMax :
                  (property_exists($field, 'maxRange') ? (int) $field->maxRange : 0);

        $viewModel['selectMode'] = $selectMode;
        $viewModel['exactCount'] = $exactCount;
        $viewModel['minRange'] = $minRange;
        $viewModel['maxRange'] = $maxRange;
        $viewModel['enableSelectAll'] = property_exists($field, 'enableSelectAll') ? (bool) $field->enableSelectAll : ($isCheckbox ? true : false);
        $viewModel['selectAllText'] = property_exists($field, 'selectAllText') ? $field->selectAllText : __('Select All', 'folkingebrew');
        $viewModel['enableOtherChoice'] = property_exists($field, 'enableOtherChoice') ? (bool) $field->enableOtherChoice : false;
        $viewModel['otherChoiceText'] = property_exists($field, 'otherChoiceText') ? $field->otherChoiceText : __('Other', 'folkingebrew');

        return $viewModel;
    }
}
