@if($label !== '')
  <label class="font-medium" for="{{ $inputId }}">
    {{ $label }} @if($isRequired)<span aria-hidden="true">*</span>@endif
  </label>
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
    <div id="{{ $ariaDescId }}" class="text-sm text-gray-600">{{ $description }}</div>
  @endif

  @if($failed && $message)
    <div class="text-sm text-red-600">{{ wp_strip_all_tags($message) }}</div>
  @endif
</div>
