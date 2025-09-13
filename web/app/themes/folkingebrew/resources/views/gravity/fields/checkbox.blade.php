@if($label !== '')
  <fieldset>
    <legend class="gfield_label gform-field-label font-medium">
      {{ $label }} @if($isRequired)<span aria-hidden="true">*</span>@endif
    </legend>
  </fieldset>
@endif

@php
  $formId = (int) ($field->formId ?? 0);
  $fieldId = (int) ($field->id ?? 0);
@endphp

<div class="ginput_container ginput_container_checkbox">
  <div class="flex flex-col gap-1">
    <div class="gfield_checkbox space-y-2">
      @foreach($choices as $index => $choice)
        @php
          $choiceValue = $choice['value'] ?? $choice['text'] ?? '';
          $choiceText = $choice['text'] ?? $choiceValue;
          $suffix = (string) ($index + 1);
          $choiceId = $inputId . '_' . $suffix; // input_{formId}_{fieldId}_{suffix}
          $subName = 'input_' . $fieldId . '.' . $suffix; // Gravity Forms expects dot notation per choice

          // Determine checked state from $value or POST fallback
          $isSelected = false;
          if (is_array($value)) {
            if (in_array($choiceValue, $value, true)) {
              $isSelected = true;
            } elseif (!empty($value[$suffix])) {
              $isSelected = true;
            } elseif (!empty($value['input_' . $fieldId . '.' . $suffix] ?? '')) {
              $isSelected = true;
            }
          }
          if (!$isSelected) {
            $postKey = 'input_' . $fieldId . '_' . $suffix; // PHP converts dots to underscores in keys
            $posted = function_exists('rgpost') ? rgpost($postKey) : ($_POST[$postKey] ?? '');
            if (is_string($posted) && $posted !== '') {
              $isSelected = true;
            }
          }
        @endphp

        <div class="gchoice flex items-center gap-2">
          <input
            type="checkbox"
            id="{{ $choiceId }}"
            name="{{ $subName }}"
            value="{{ $choiceValue }}"
            @if($isSelected) checked @endif
            @if($isRequired) aria-required="true" @endif
            @if($failed) aria-invalid="true" @endif
            class="gfield-choice-input @if($failed) border-red-500 @else border-gray-300 @endif"
          />
          <label for="{{ $choiceId }}" class="gform-field-label gform-field-label--type-inline">
            {{ $choiceText }}
          </label>
        </div>
      @endforeach
    </div>

    @if($description)
      <p id="{{ $ariaDescId }}" class="text-sm text-gray-600">{{ $description }}</p>
    @endif

    @if($failed && $message)
      <p class="text-sm text-red-600">{{ wp_strip_all_tags($message) }}</p>
    @endif
  </div>
</div>
