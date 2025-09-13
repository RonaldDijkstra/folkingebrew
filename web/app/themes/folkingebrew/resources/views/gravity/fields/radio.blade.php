@if($label !== '')
  <fieldset>
    <legend class="gfield_label gform-field-label font-medium">
      {{ $label }} @if($isRequired)<span aria-hidden="true">*</span>@endif
    </legend>
  </fieldset>
@endif

<div class="ginput_container ginput_container_radio">
  <div class="flex flex-col gap-1">
    <div class="gfield_radio space-y-2">
      @foreach($choices as $index => $choice)
        @php
          $choiceValue = $choice['value'] ?? $choice['text'] ?? '';
          $choiceText = $choice['text'] ?? $choiceValue;
          $choiceId = $inputId . '_' . ($index + 1);
          $isSelected = (string) ($value ?? '') === (string) $choiceValue;
        @endphp

        <div class="gchoice flex items-center gap-2">
          <input
            type="radio"
            id="{{ $choiceId }}"
            name="{{ $inputName }}"
            value="{{ $choiceValue }}"
            @if($isSelected) checked @endif
            @if($isRequired) aria-required="true" required @endif
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
