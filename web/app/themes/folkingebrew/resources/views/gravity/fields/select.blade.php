@if($label !== '')
  <label class="font-medium" for="{{ $inputId }}">
    {{ $label }} @if($isRequired)<span aria-hidden="true">*</span>@endif
  </label>
@endif

<div class="flex flex-col gap-1">
  <select
    id="{{ $inputId }}"
    name="{{ $inputName }}"
    @if($isRequired) aria-required="true" required @endif
    @if($failed) aria-invalid="true" @endif
    class="border rounded px-3 py-2 w-full @if($failed) border-red-500 @else border-gray-300 @endif"
    aria-describedby="{{ $ariaDescId }}"
  >
    @foreach($choices as $c)
      @php
        $optValue = $c['value'] ?? $c['text'] ?? '';
        $optText  = $c['text'] ?? $optValue;
        $selected = (string) ($value ?? '') !== '' && (string) $value === (string) $optValue;
      @endphp
      <option value="{{ $optValue }}" @if($selected) selected @endif>{{ $optText }}</option>
    @endforeach
  </select>

  @if($description)
    <p id="{{ $ariaDescId }}" class="text-sm text-gray-600">{{ $description }}</p>
  @endif

  @if($failed && $message)
    <p class="text-sm text-red-600">{{ wp_strip_all_tags($message) }}</p>
  @endif
</div>
