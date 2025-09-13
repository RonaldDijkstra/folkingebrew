@if($label !== '')
  <fieldset class="multiple-choice-field">
    <legend class="font-medium">
      {{ $label }} @if($isRequired)<span aria-hidden="true">*</span>@endif
    </legend>
  </fieldset>
@endif

@php
  $formId = (int) ($field->formId ?? 0);
  $fieldId = (int) ($field->id ?? 0);

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

  // Determine if this is a multiple selection mode (for UI purposes)
  $isMultipleSelection = in_array($selectMode, ['multiple', 'range', 'exactly']);

  // Advanced feature settings
  $exactCount = ($selectMode === 'exactly') ? $choiceLimitNumber :
                (property_exists($field, 'exactCount') ? (int) $field->exactCount : 1);
  $minRange = ($selectMode === 'range') ? $choiceLimitMin :
              (property_exists($field, 'minRange') ? (int) $field->minRange : 0);
  $maxRange = ($selectMode === 'range') ? $choiceLimitMax :
              (property_exists($field, 'maxRange') ? (int) $field->maxRange : 0);
  // Enable select all by default for checkbox fields for testing
  $enableSelectAll = property_exists($field, 'enableSelectAll') ? (bool) $field->enableSelectAll : ($isCheckbox ? true : false);
  $selectAllText = property_exists($field, 'selectAllText') ? $field->selectAllText : __('Select All', 'folkingebrew');
  $enableOther = property_exists($field, 'enableOtherChoice') ? (bool) $field->enableOtherChoice : false;
  // Disable other option for checkbox fields as they don't support it
  if ($isCheckbox) {
    $enableOther = false;
  }
  $otherText = property_exists($field, 'otherChoiceText') ? $field->otherChoiceText : __('Other', 'folkingebrew');

  // For radio fields, force single mode unless explicitly configured
  if ($isRadio && $selectMode !== 'single') {
    // Radio with advanced features still uses radio inputs but with validation
    $inputType = 'radio';
  } elseif ($isCheckbox) {
    $inputType = 'checkbox';
  } else {
    $inputType = 'radio';
  }

  // Build unique field identifier for JavaScript
  $fieldIdentifier = "mc_field_{$formId}_{$fieldId}";

  // Check if this field has any advanced features enabled
  $hasAdvancedFeatures = (
    $enableSelectAll ||
    $enableOther ||
    $isMultipleSelection ||
    ($minRange > 0 || $maxRange > 0)
  );
@endphp

<div class="flex flex-col gap-1 multiple-choice-container"
     data-field-id="{{ $fieldId }}"
     data-form-id="{{ $formId }}"
     data-select-mode="{{ $selectMode }}"
     data-exact-count="{{ $exactCount }}"
     data-min-range="{{ $minRange }}"
     data-max-range="{{ $maxRange }}"
     data-enable-select-all="{{ $enableSelectAll ? 'true' : 'false' }}"
     data-enable-other="{{ $enableOther ? 'true' : 'false' }}"
     data-select-all-text="{{ $selectAllText }}">

  {{-- Select All Button (for checkbox modes only) --}}
  @if($inputType === 'checkbox' && $enableSelectAll && $isMultipleSelection)
    <div class="select-all-container mb-2">
      <button type="button"
              class="select-all-btn bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded text-sm transition-colors"
              data-target="{{ $fieldIdentifier }}">
        {{ $selectAllText }}
      </button>
    </div>
  @endif


  <div class="space-y-2 choices-container" id="{{ $fieldIdentifier }}">
    @foreach($choices as $index => $choice)
      @php
        $choiceValue = $choice['value'] ?? $choice['text'] ?? '';
        $choiceText = $choice['text'] ?? $choiceValue;
        $isDefault = (bool) ($choice['isSelected'] ?? false);
        $suffix = (string) ($index + 1);
        $choiceId = $inputId . '_' . $suffix;

        // Name handling for different input types - use same logic as original templates
        if ($inputType === 'radio') {
          $subName = $inputName; // Radio buttons share the same name
        } else {
          // For checkboxes, use the same naming as the original checkbox template
          $subName = 'input_' . $fieldId . '.' . $suffix; // Gravity Forms expects dot notation per choice
        }

        // Determine checked state - prioritize submitted values, then defaults
        $isSelected = false;
        $hasSubmittedData = false;

        // Check for submitted values first
        if (is_array($value) && !empty($value)) {
          $hasSubmittedData = true;
          if ($inputType === 'radio') {
            $isSelected = (in_array($choiceValue, $value, true) || (isset($value[0]) && $value[0] === $choiceValue));
          } else {
            if (in_array($choiceValue, $value, true)) {
              $isSelected = true;
            } elseif (!empty($value[$suffix])) {
              $isSelected = true;
            } elseif (!empty($value['input_' . $fieldId . '.' . $suffix] ?? '')) {
              $isSelected = true;
            }
          }
        } elseif (is_string($value) && $value !== '' && $inputType === 'radio') {
          $hasSubmittedData = true;
          $isSelected = ($value === $choiceValue);
        }

        // Check POST data if no value array
        if (!$hasSubmittedData) {
          if ($inputType === 'radio') {
            $posted = function_exists('rgpost') ? rgpost($inputName) : ($_POST[$inputName] ?? '');
            if ($posted && $posted !== '') {
              $hasSubmittedData = true;
              $isSelected = ($posted === $choiceValue);
            }
          } else {
            $postKey = 'input_' . $fieldId . '_' . $suffix;
            $posted = function_exists('rgpost') ? rgpost($postKey) : ($_POST[$postKey] ?? '');
            if ($posted && $posted !== '') {
              $hasSubmittedData = true;
              $isSelected = true;
            }
          }
        }

        // Use defaults only if no submitted data
        if (!$hasSubmittedData && $isDefault) {
          $isSelected = true;
        }
      @endphp

      <div class="flex items-center gap-2 choice-item gchoice">
        @if($inputType === 'radio')
          @include('gravity.fields.inputs.radio', [
            'id' => $choiceId,
            'label' => $choiceText,
            'name' => $subName,
            'value' => $choiceValue,
            'checked' => $isSelected,
            'isRequired' => $isRequired,
            'failed' => $failed,
            'classes' => 'choice-input',
            'data-choice-index' => $index,
          ])
        @elseif($inputType === 'checkbox')
         @include('gravity.fields.inputs.checkbox', [
           'id' => $choiceId,
           'label' => $choiceText,
           'name' => $subName,
           'value' => $choiceValue,
           'checked' => $isSelected,
           'isRequired' => $isRequired,
           'failed' => $failed,
           'classes' => 'choice-input',
           'data-choice-index' => $index,
         ])
        @else
          <input
            type="{{ $inputType }}"
            id="{{ $choiceId }}"
            name="{{ $subName }}"
            value="{{ $choiceValue }}"
            @if($isSelected) checked @endif
            @if($isRequired) aria-required="true" @endif
            @if($failed) aria-invalid="true" @endif
            class="choice-input @if($failed) border-red-500 @else border-gray-300 @endif"
            data-choice-index="{{ $index }}"
          />
          <label for="{{ $choiceId }}" class="choice-label">
            {{ $choiceText }}
          </label>
        @endif
      </div>
    @endforeach

    {{-- Other Choice Input --}}
    @if($enableOther)
      @php
        $otherSuffix = (string) (count($choices) + 1);
        $otherChoiceId = $inputId . '_' . $otherSuffix;
        $otherTextId = $inputId . '_other_text';

        if ($inputType === 'radio') {
          $otherSubName = $inputName;
          $otherTextName = 'input_' . $fieldId . '_other';
        } else {
          // For checkboxes, the "other" text should be stored in the same field as the checkbox
          $otherSubName = 'input_' . $fieldId . '.' . $otherSuffix;
          $otherTextName = 'input_' . $fieldId . '.' . $otherSuffix;
        }

        // Check if other is selected
        $otherSelected = false;
        $otherTextValue = '';

        if (function_exists('rgpost')) {
          if ($inputType === 'radio') {
            $otherPost = rgpost('input_' . $fieldId . '_other');
            $otherSelected = !empty($otherPost);
            $otherTextValue = $otherPost ?: '';
          } else {
            // For checkboxes, check if the "other" field has a value that's not "gf_other_choice"
            $otherPost = rgpost('input_' . $fieldId . '_' . $otherSuffix);
            $otherSelected = !empty($otherPost);
            if ($otherSelected && $otherPost !== 'gf_other_choice') {
              $otherTextValue = $otherPost;
            } else {
              $otherTextValue = '';
            }
          }
        }
      @endphp

      <div class="flex items-center gap-2 choice-item other-choice gchoice">
        @if($inputType === 'radio')
          @include('gravity.fields.inputs.radio', [
            'id' => $otherChoiceId,
            'label' => $otherText,
            'name' => $otherSubName,
            'value' => 'gf_other_choice',
            'checked' => $otherSelected,
            'isRequired' => $isRequired,
            'failed' => $failed,
            'classes' => 'choice-input other-choice-input',
            'data-choice-index' => count($choices),
          ])
        @elseif($inputType === 'checkbox')
          @include('gravity.fields.inputs.checkbox', [
            'id' => $otherChoiceId,
            'label' => $otherText,
            'name' => $otherSubName,
            'value' => 'gf_other_choice',
            'checked' => $otherSelected,
            'isRequired' => $isRequired,
            'failed' => $failed,
            'classes' => 'choice-input other-choice-input',
            'data-choice-index' => count($choices),
          ])
        @else
          <input
            type="{{ $inputType }}"
            id="{{ $otherChoiceId }}"
            name="{{ $otherSubName }}"
            value="gf_other_choice"
            @if($otherSelected) checked @endif
            @if($isRequired) aria-required="true" @endif
            @if($failed) aria-invalid="true" @endif
            class="choice-input @if($failed) border-red-500 @else border-gray-300 @endif"
            data-choice-index="{{ $index }}"
          />
          <label for="{{ $otherChoiceId }}" class="choice-label">
            {{ $otherText }}
          </label>
        @endif
        <input
          type="text"
          id="{{ $otherTextId }}"
          name="{{ $otherTextName }}"
          value="{{ $otherTextValue }}"
          placeholder="{{ __('Please specify...', 'folkingebrew') }}"
          class="other-text-input border rounded px-2 py-1 ml-2 @if($failed) border-red-500 @else border-gray-300 @endif @if(!$otherSelected) opacity-50 @endif"
          @if(!$otherSelected) disabled @endif
        />
      </div>
    @endif
  </div>

  {{-- Selection Information (below checkboxes) --}}
  {{-- Range Settings Display (for range mode) --}}
  @if($selectMode === 'range')
    <div class="range-info mt-2 text-sm text-gray-600">
      @if($minRange > 0 && $maxRange > 0)
        {{ sprintf(__('Select between %d and %d options', 'folkingebrew'), $minRange, $maxRange) }}
      @elseif($minRange > 0)
        {{ sprintf(__('Select at least %d options', 'folkingebrew'), $minRange) }}
      @elseif($maxRange > 0)
        {{ sprintf(__('Select up to %d options', 'folkingebrew'), $maxRange) }}
      @endif
    </div>
  @endif

  {{-- Exact Count Display (for exactly mode) --}}
  @if($selectMode === 'exactly')
    <div class="exact-info mt-2 text-sm text-gray-600">
      {{ sprintf(__('Select exactly %d options', 'folkingebrew'), $exactCount) }}
    </div>
  @endif


  {{-- Validation Messages --}}
  @if($selectMode === 'exactly')
    <div class="validation-message exactly-message text-sm text-red-600" style="display: none;">
      {{ sprintf(__('Please select exactly %d options.', 'folkingebrew'), $exactCount) }}
    </div>
  @endif

  @if($selectMode === 'range')
    <div class="validation-message range-message text-sm text-red-600" style="display: none;">
      @if($minRange > 0 && $maxRange > 0)
        {{ sprintf(__('Please select between %d and %d options.', 'folkingebrew'), $minRange, $maxRange) }}
      @elseif($minRange > 0)
        {{ sprintf(__('Please select at least %d options.', 'folkingebrew'), $minRange) }}
      @elseif($maxRange > 0)
        {{ sprintf(__('Please select up to %d options.', 'folkingebrew'), $maxRange) }}
      @endif
    </div>
  @endif

  @if($description)
    <p id="{{ $ariaDescId }}" class="text-sm text-gray-600">{{ $description }}</p>
  @endif

  @if($failed && $message)
    <p class="text-sm text-red-600">{{ wp_strip_all_tags($message) }}</p>
  @endif
</div>