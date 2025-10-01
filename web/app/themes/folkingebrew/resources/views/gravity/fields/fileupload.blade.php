@if($label !== '')
  @include('gravity.label', [
    'label' => $label,
    'isRequired' => $isRequired,
    'inputId' => $inputId,
  ])
@endif

<div class="flex flex-col gap-1">
  <div class="file-upload-wrapper">
    <input
      type="file"
      id="{{ $inputId }}"
      name="{{ $inputName }}"
      @if($isRequired) aria-required="true" required @endif
      @if($failed) aria-invalid="true" @endif
      aria-describedby="{{ $ariaDescId }}"
      class="block w-full text-sm text-body file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary/20 file:text-primary hover:file:bg-primary/30 border rounded @if($failed) border-red-500 @else border-gray-300 @endif"
      @if(isset($field) && property_exists($field, 'multipleFiles') && $field->multipleFiles) multiple @endif
      @if(isset($field) && property_exists($field, 'allowedExtensions') && $field->allowedExtensions) accept="{{ $field->allowedExtensions }}" @endif
    />

    @if(isset($field) && property_exists($field, 'maxFileSize') && $field->maxFileSize)
      <div class="text-xs text-gray-500 mt-1">
         {{ __('Maximum file size', 'folkingebrew') }}: {{ $field->maxFileSize }} MB
      </div>
    @endif

    @if(isset($field) && property_exists($field, 'allowedExtensions') && $field->allowedExtensions)
      <div class="text-xs text-gray-500 mt-1">
        {{ __('Allowed file types', 'folkingebrew') }}: {{ $field->allowedExtensions }}
      </div>
    @endif
  </div>

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
