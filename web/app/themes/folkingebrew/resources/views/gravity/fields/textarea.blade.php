@if($label !== '')
  <label class="font-medium" for="{{ $inputId }}">
    {{ $label }} @if($isRequired)<span aria-hidden="true">*</span>@endif
  </label>
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
    class="border rounded px-3 py-2 w-full @if($failed) border-red-500 @else border-gray-300 @endif"
  >{{ is_string($value) ? $value : '' }}</textarea>

  @if($description)
    <p id="{{ $ariaDescId }}" class="text-sm text-gray-600">{{ $description }}</p>
  @endif

  @if($failed && $message)
    <p class="text-sm text-red-600">{{ wp_strip_all_tags($message) }}</p>
  @endif
</div>
