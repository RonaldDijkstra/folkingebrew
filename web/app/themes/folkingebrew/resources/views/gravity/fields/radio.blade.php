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

  // Check for other choice setting
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
        <input
          type="radio"
          id="{{ $choiceId }}"
          name="{{ $inputName }}"
          value="{{ $choiceValue }}"
          @if($isSelected) checked @endif
          @if($isRequired) aria-required="true" required @endif
          @if($failed) aria-invalid="true" @endif
          class="@if($failed) border-red-500 @else border-gray-300 @endif"
        />
        <label for="{{ $choiceId }}">
          {{ $choiceText }}
        </label>
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

        if (function_exists('rgpost')) {
          $otherPost = rgpost('input_' . $fieldId . '_other');
          $otherSelected = !empty($otherPost);
          $otherTextValue = $otherPost ?: '';
        }
      @endphp

      <div class="gchoice flex items-center gap-2 other-choice">
        <input
          type="radio"
          id="{{ $otherChoiceId }}"
          name="{{ $inputName }}"
          value="gf_other_choice"
          @if($otherSelected) checked @endif
          @if($isRequired) aria-required="true" @endif
          @if($failed) aria-invalid="true" @endif
          class="other-choice-input @if($failed) border-red-500 @else border-gray-300 @endif"
        />
        <label for="{{ $otherChoiceId }}">
          {{ $otherText }}
        </label>
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

  @if($description)
    <p id="{{ $ariaDescId }}" class="text-sm text-gray-600">{{ $description }}</p>
  @endif

  @if($failed && $message)
    <p class="text-sm text-red-600">{{ wp_strip_all_tags($message) }}</p>
  @endif
</div>

