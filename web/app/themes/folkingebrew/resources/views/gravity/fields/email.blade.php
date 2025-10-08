@if($field->emailConfirmEnabled)
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


    <div class="grid md:grid-cols-2 gap-2" id="input_{{ $formId }}_{{ $field->id }}_container">
      @foreach($field->inputs as $index => $input)
        @php
          $inputNumber = $index + 1;
          $subInputId = 'input_' . $formId . '_' . $field->id . ($inputNumber > 1 ? '_' . $inputNumber : '');
          $subInputName = 'input_' . $field->id . ($inputNumber > 1 ? '_' . $inputNumber : '');
          $subInputValue = is_array($value) ? ($value[$input['id']] ?? '') : ($inputNumber === 1 ? $value : '');
          $positionClass = $inputNumber === 1 ? 'order-1' : 'order-2';
        @endphp
        <div id="{{ $subInputId }}_container">
          @include('gravity.fields.inputs.text', [
            'id' => $subInputId,
            'name' => $subInputName,
            'value' => $subInputValue,
            'placeholder' => __('Enter Email', 'folkingebrew'),
            'isRequired' => $isRequired,
            'failed' => $failed,
            'ariaDescId' => $ariaDescId,
            'classes' => 'w-full mb-1',
            'autocomplete' => $autocomplete,
            'autocompleteAttribute' => $autocompleteAttribute,
          ])
          <label for="{{ $subInputId }}" class="block text-sm">
            {{ $input['label'] ?? ($inputNumber === 1 ? 'Enter Email' : 'Confirm Email') }}
          </label>
        </div>
      @endforeach
      <div class="gf_clear gf_clear_complex"></div>
    </div>

  @if($failed && $message)
    @include('gravity.validation-field', [
      'message' => $message,
    ])
  @endif

  @if($description && $descriptionPlacement == 'below')
    @include('gravity.description', [
      'description' => $description,
      'ariaDescId' => $ariaDescId,
    ])
  @endif
@else
  @include('gravity.fields.text', [
    'type' => 'email'
  ])
@endif
