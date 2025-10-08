@if($visibility === 'administrative') @php return; @endphp @endif

@if($label !== '')
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
