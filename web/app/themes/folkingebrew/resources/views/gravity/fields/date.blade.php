@if($label !== '')
  @include('gravity.label', [
    'label' => $label,
    'isRequired' => $isRequired,
    'inputId' => $inputId,
  ])
@endif

<div class="flex flex-col gap-1">
  @if($field->dateType === 'datepicker')
    @include('gravity.fields.date.datepicker')
  @endif

  @if($field->dateType === 'datefield')
    @include('gravity.fields.date.datefield')
  @endif

  @if($field->dateType === 'datedropdown')
    @include('gravity.fields.date.datedropdown')
  @endif

  @if($description)
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
