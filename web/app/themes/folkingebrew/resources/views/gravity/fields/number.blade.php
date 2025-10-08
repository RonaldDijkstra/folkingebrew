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
  <div class="relative {{ $size === 'small' ? 'sm:w-1/3' : ($size === 'medium' ? 'sm:w-1/2' : 'w-full ')}}">
    <button
      type="button"
      id="decreaseButton"
      class="absolute right-9 top-2 rounded bg-primary p-1.5 border border-transparent text-center text-sm text-white transition-all shadow-sm hover:shadow focus:bg-primary/80 focus:shadow-none active:bg-primary/80 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
    >
      <x-icon name="minus" classes="w-4 h-4" />
    </button>
    <input
      type="number"
      step="1"
      @if($min !== '') min="{{ $min }}" @endif
      @if($max !== '') max="{{ $max }}" @endif
      @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
      id="{{ $inputId }}"
      name="{{ $inputName }}"
      value="{{ $value ?: ($min !== '' ? $min : '') }}"
      class="border w-full text-body rounded px-3 py-2 focus:outline-none focus:border-primary focus:shadow appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none @if($failed) border-red-500 @else border-gray-300 @endif"
      @if($isRequired) required @endif
    />
    <button
      type="button"
      id="increaseButton"
      class="absolute right-1 top-2 rounded bg-primary p-1.5 border border-transparent text-center text-sm text-white transition-all shadow-sm hover:shadow focus:bg-primary/80 focus:shadow-none active:bg-primary/80 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
    >
      <x-icon name="plus" classes="w-4 h-4" />
    </button>
  </div>

  @if(!empty($rangeText))
    <div class="text-sm text-body mt-1">
      {{ $rangeText }}
    </div>
  @endif

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
