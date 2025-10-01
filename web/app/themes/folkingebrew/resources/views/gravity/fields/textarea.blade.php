@if($label !== '')
  @include('gravity.label', [
    'label' => $label,
    'isRequired' => $isRequired,
    'inputId' => $inputId,
  ])
@endif

<div class="flex flex-col gap-1">
  <textarea
    id="{{ $inputId }}"
    name="{{ $inputName }}"
    rows="5"
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($isRequired) aria-required="true" required @endif
    @if($failed) aria-invalid="true" @endif
    aria-describedby="{{ $ariaDescId }}"
    class="border rounded px-3 py-2 w-full focus:outline-primary @if($failed) border-red-500 @else border-gray-300 @endif"
  >{{ is_string($value) ? $value : '' }}</textarea>

  @if($description)
    @include('gravity.description', [
      'message' => $message,
      'ariaDescId' => $ariaDescId,
    ])
  @endif

  @if($failed && $message)
    @include('gravity.validation-field', [
      'message' => $message,
    ])
  @endif
</div>
