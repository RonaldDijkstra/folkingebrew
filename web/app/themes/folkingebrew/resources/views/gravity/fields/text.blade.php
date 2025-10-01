@if($label !== '')
  @include('gravity.label', [
    'label' => $label,
    'isRequired' => $isRequired,
    'inputId' => $inputId,
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

  @if($description)
    <div id="{{ $ariaDescId }}" class="text-sm text-gray-600">{{ $description }}</div>
  @endif

  @if($failed && $message)
    @include('gravity.validation-field', [
      'message' => $message,
    ])
  @endif
</div>
