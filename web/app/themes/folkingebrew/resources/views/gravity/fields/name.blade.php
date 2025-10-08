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

<div class="flex flex-col gap-1">
  <div class="flex flex-col sm:flex-row sm:flex-wrap gap-2">
    @if(property_exists($field, 'inputs') && is_array($field->inputs))
      @foreach($field->inputs as $input)
        @if(!empty($input) && ($input['isHidden'] ?? false) !== true)
          <div class="w-full sm:flex-1 sm:min-w-0" style="min-width: 120px;">
            @if(!empty($input['label']))
            <label class="block text-xs text-gray-600 mb-1" for="input_{{ $fieldId }}_{{ $input['id'] }}">
                {{ $input['label'] }}
            </label>
            @endif
            <input
              type="text"
              id="input_{{ $fieldId }}_{{ $input['id'] }}"
              name="input_{{ $input['id'] }}"
              value="{{ is_array($value) && isset($value[$input['id']]) ? $value[$input['id']] : '' }}"
              @if($autocomplete) autocomplete="{{ $input['autocompleteAttribute'] }}" @endif
              @if(!empty($input['placeholder'])) placeholder="{{ $input['placeholder'] }}" @endif
              @if($isRequired) aria-required="true" @endif
              @if($failed) aria-invalid="true" @endif
              aria-describedby="{{ $ariaDescId }}"
              class="border rounded px-3 py-2 w-full @if($failed) border-red-500 @else border-gray-300 @endif"
            />
          </div>
        @endif
      @endforeach
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