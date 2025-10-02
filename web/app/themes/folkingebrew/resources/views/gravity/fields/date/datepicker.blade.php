<div class="relative w-1/3">
  <input
    id="{{ $inputId }}"
    name="{{ $inputName }}"
    type="text"
    value="{{ is_string($value) ? $value : '' }}"
    placeholder="{{ $placeholder ?: __('Select a date', 'folkingebrew') }}"
    @if($isRequired) aria-required="true" required @endif
    @if($failed) aria-invalid="true" @endif
    aria-describedby="{{ $ariaDescId }}"
    class="flatpickr-input border rounded px-3 py-2 pr-10 w-full @if($failed) border-red-500 @else border-gray-300 @endif"
    @if($noPastDates) data-no-past-dates="true" @endif
    readonly
  >
  <figure class="absolute top-1/2 -translate-y-1/2 right-3 pointer-events-none z-10">
    <x-icon name="calendar" classes="w-4 h-4 text-body" />
  </figure>
</div>