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
@endphp

<div class="flex flex-col gap-1">
  <div class="space-y-2">
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

      <div class="flex items-center gap-2">
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
