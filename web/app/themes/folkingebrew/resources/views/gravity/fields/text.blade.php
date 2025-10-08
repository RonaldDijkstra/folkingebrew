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
  @include('gravity.fields.inputs.text', [
    'type' => $type,
    'id' => $inputId,
    'name' => $inputName,
    'value' => $value,
    'placeholder' => $placeholder,
    'isRequired' => $isRequired,
    'failed' => $failed,
    'classes' => ($size === 'small' ? 'w-1/3' : ($size === 'medium' ? 'w-1/2' : 'w-full ')),
  ])

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
