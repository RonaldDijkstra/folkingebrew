@if($label !== '' && $field->labelPlacement !== 'hidden_label')
  @include('gravity.label', [
    'label' => $label,
    'isRequired' => $isRequired,
    'inputId' => $inputId,
  ])
@endif

@if($description && $descriptionPlacement == 'above')
  @include('gravity.description', [
    'description' => $description,
    'ariaDescId' => $ariaDescId,
  ])
@endif

<div class="flex flex-col gap-1">
  <input
    type="{{ $inputType }}"
    id="{{ $inputId }}"
    name="{{ $inputName }}"
    value="{{ is_string($value) ? $value : '' }}"
    autocomplete="{{ $autocompleteValue }}"
    pattern="[0-9\s\-\+\(\)\.x#]+"
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($isRequired) aria-required="true" required @endif
    @if($failed) aria-invalid="true" @endif
    aria-describedby="{{ $ariaDescId }}"
    class="border rounded px-3 py-2 @if($size === 'small') w-1/3 @elseif($size === 'medium') w-1/2 @else w-full @endif @if($failed) border-red-500 @else border-gray-300 @endif"
    oninput="this.value = this.value.replace(/[^0-9\s\-\+\(\)\.x#]/g, '')"
  />

  @if($description && $descriptionPlacement != 'above')
    @include('gravity.description', [
      'description' => $description,
      'ariaDescId' => $ariaDescId,
    ])
  @endif

  @if($failed && $message)
    @include('gravity.validation-field', [
      'message' => $message,
    ])
  @endif
</div>
