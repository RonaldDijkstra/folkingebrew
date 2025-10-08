@if($visibility === 'administrative') @php return; @endphp @endif

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

<div class="time-field flex flex-col gap-1">
  <div class="flex items-center gap-2">
    {{-- Hour Input --}}
    <div class="flex flex-col">
      <label for="{{ $inputId }}_1" class="text-xs text-gray-600 mb-1">
        {{ $subFieldLabels['hour'] }}
      </label>
      <input
        id="{{ $inputId }}_1"
        name="{{ $inputName }}_1"
        type="number"
        value="{{ $valuesBySuffix['1'] }}"
        placeholder="{{ $inputAttributes['hour']['placeholder'] }}"
        min="{{ $inputAttributes['hour']['min'] }}"
        max="{{ $inputAttributes['hour']['max'] }}"
        step="{{ $inputAttributes['hour']['step'] }}"
        @if($isRequired) aria-required="true" required @endif
        @if($failed) aria-invalid="true" @endif
        aria-describedby="{{ $ariaDescId }}"
        class="{{ $inputAttributes['classes']['base'] }} {{ $inputAttributes['classes']['error'] }}"
      />
    </div>

    {{-- Separator --}}
    <div class="text-xl font-medium mt-6">:</div>

    {{-- Minute Input --}}
    <div class="flex flex-col">
      <label for="{{ $inputId }}_2" class="text-xs text-gray-600 mb-1">
        {{ $subFieldLabels['minute'] }}
      </label>
      <input
        id="{{ $inputId }}_2"
        name="{{ $inputName }}_2"
        type="number"
        value="{{ $valuesBySuffix['2'] }}"
        placeholder="{{ $inputAttributes['minute']['placeholder'] }}"
        min="{{ $inputAttributes['minute']['min'] }}"
        max="{{ $inputAttributes['minute']['max'] }}"
        step="{{ $inputAttributes['minute']['step'] }}"
        @if($isRequired) aria-required="true" required @endif
        @if($failed) aria-invalid="true" @endif
        aria-describedby="{{ $ariaDescId }}"
        class="{{ $inputAttributes['classes']['base'] }} {{ $inputAttributes['classes']['error'] }}"
      />
    </div>

    {{-- AM/PM Dropdown (only for 12-hour format) --}}
    @if($is12Hour)
      <div class="flex flex-col">
        <label for="{{ $inputId }}_3" class="text-xs text-gray-600 mb-1">
          {{ $subFieldLabels['ampm'] }}
        </label>
        <select
          id="{{ $inputId }}_3"
          name="{{ $inputName }}_3"
          @if($isRequired) aria-required="true" required @endif
          @if($failed) aria-invalid="true" @endif
          aria-describedby="{{ $ariaDescId }}"
          class="{{ $inputAttributes['classes']['base'] }} {{ $inputAttributes['classes']['error'] }}"
        >
           <option value="" disabled {{ empty($valuesBySuffix['3']) ? 'selected' : '' }}>{{ __('Select', 'folkingebrew') }}</option>
          <option value="AM" {{ $valuesBySuffix['3'] === 'AM' ? 'selected' : '' }}>AM</option>
          <option value="PM" {{ $valuesBySuffix['3'] === 'PM' ? 'selected' : '' }}>PM</option>
        </select>
      </div>
    @endif
  </div>

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
