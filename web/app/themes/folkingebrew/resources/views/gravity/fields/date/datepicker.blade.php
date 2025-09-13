<input
  id="{{ $inputId }}"
  name="{{ $inputName }}"
  type="text"
  value="{{ is_string($value) ? $value : '' }}"
  placeholder="{{ $placeholder ?: __('Select a date', 'folkingebrew') }}"
  @if($isRequired) aria-required="true" required @endif
  @if($failed) aria-invalid="true" @endif
  aria-describedby="{{ $ariaDescId }}"
  class="flatpickr-input border rounded px-3 py-2 w-full @if($failed) border-red-500 @else border-gray-300 @endif"
  @if($noPastDates) data-no-past-dates="true" @endif
  readonly
/>
