@php
  $t = $htmlInputType ?? ($type === 'email' ? 'email' : ($type === 'number' ? 'number' : 'text'));
@endphp

@if($label !== '')
  <label class="font-medium" for="{{ $inputId }}">
    {{ $label }} @if($isRequired)<span aria-hidden="true">*</span>@endif
  </label>
@endif

<div class="flex flex-col gap-1">
  <input
    id="{{ $inputId }}"
    name="{{ $inputName }}"
    type="{{ $t }}"
    value="{{ is_string($value) ? $value : '' }}"
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($isRequired) aria-required="true" required @endif
    @if($failed) aria-invalid="true" @endif
    aria-describedby="{{ $ariaDescId }}"
    class="border rounded px-3 py-2 @if($size === 'small') w-1/3 @elseif($size === 'medium') w-1/2 @else w-full @endif @if($failed) border-red-500 @else border-gray-300 @endif"
  />

  @if($description)
    <div id="{{ $ariaDescId }}" class="text-sm text-gray-600">{{ $description }}</div>
  @endif

  @if($failed && $message)
    <div class="text-sm text-red-600">{{ wp_strip_all_tags($message) }}</div>
  @endif
</div>
