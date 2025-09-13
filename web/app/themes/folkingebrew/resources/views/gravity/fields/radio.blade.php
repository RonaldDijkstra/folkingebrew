@if($label !== '')
  <fieldset>
    <legend class="font-medium">
      {{ $label }} @if($isRequired)<span aria-hidden="true">*</span>@endif
    </legend>
  </fieldset>
@endif

@php
  $formId = (int) ($field->formId ?? 0);
  $fieldId = (int) ($field->id ?? 0);
  $enableOther = property_exists($field, 'enableOtherChoice') ? (bool) $field->enableOtherChoice : false;
  $otherText = property_exists($field, 'otherChoiceText') ? $field->otherChoiceText : __('Other', 'folkingebrew');
@endphp

<div class="flex flex-col gap-1">
  <div class="space-y-2">
    @foreach($choices as $index => $choice)
      @php
        $choiceValue = $choice['value'] ?? $choice['text'] ?? '';
        $choiceText = $choice['text'] ?? $choiceValue;
        $isDefault = (bool) ($choice['isSelected'] ?? false);
        $choiceId = $inputId . '_' . ($index + 1);

        // Determine if this choice is selected
        $isSelected = false;
        $hasSubmittedData = false;

        // Check for submitted values first
        if (is_string($value) && $value !== '') {
          $hasSubmittedData = true;
          $isSelected = ($value === $choiceValue);
        } else {
          // Check POST data
          $posted = function_exists('rgpost') ? rgpost($inputName) : ($_POST[$inputName] ?? '');
          if ($posted && $posted !== '') {
            $hasSubmittedData = true;
            $isSelected = ($posted === $choiceValue);
          }
        }

        // Use defaults only if no submitted data
        if (!$hasSubmittedData && $isDefault) {
          $isSelected = true;
        }
      @endphp

      <div class="gchoice flex items-center gap-2">
        @include('gravity.fields.inputs.radio', [
          'id' => $choiceId,
          'label' => $choiceText,
          'name' => $inputName,
          'value' => $choiceValue,
          'checked' => $isSelected,
          'isRequired' => $isRequired,
          'failed' => $failed,
        ])
      </div>
    @endforeach

    {{-- Other Choice Input --}}
    @if($enableOther)
      @php
        $otherSuffix = (string) (count($choices) + 1);
        $otherChoiceId = $inputId . '_' . $otherSuffix;
        $otherTextId = $inputId . '_other_text';
        $otherTextName = 'input_' . $fieldId . '_other';

        // Check if other is selected
        $otherSelected = false;
        $otherTextValue = '';

        // Check if the main radio field has "gf_other_choice" selected
        $mainRadioValue = '';
        if (is_string($value) && $value !== '') {
          $mainRadioValue = $value;
        } else {
          $posted = function_exists('rgpost') ? rgpost($inputName) : ($_POST[$inputName] ?? '');
          $mainRadioValue = $posted ?: '';
        }

        $otherSelected = ($mainRadioValue === 'gf_other_choice');

        if (function_exists('rgpost')) {
          $otherPost = rgpost('input_' . $fieldId . '_other');
          $otherTextValue = $otherPost ?: '';
        }
      @endphp

      <div class="gchoice flex items-center gap-2 other-choice">
        @include('gravity.fields.inputs.radio', [
          'id' => $otherChoiceId,
          'label' => $otherText,
          'name' => $inputName,
          'value' => 'gf_other_choice',
          'checked' => $otherSelected,
          'isRequired' => $isRequired,
          'failed' => $failed,
          'classes' => 'other-choice-input',
        ])
        @include('gravity.fields.inputs.text', [
          'type' => 'text',
          'id' => $otherTextId,
          'name' => $otherTextName,
          'value' => $otherTextValue,
          'placeholder' => __('Please specify', 'folkingebrew'),
          'isRequired' => $isRequired,
          'failed' => $failed,
          'classes' => 'other-text-input',
        ])
      </div>
    @endif
  </div>

  @if($description)
    <p id="{{ $ariaDescId }}" class="text-sm text-gray-600">{{ $description }}</p>
  @endif

  @if($failed && $message)
    <p class="text-sm text-red-600">{{ wp_strip_all_tags($message) }}</p>
  @endif
</div>

