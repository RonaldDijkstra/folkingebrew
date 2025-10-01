@if($label !== '')
  <label class="font-medium" for="{{ $inputId }}">
    {{ $label }} @if($isRequired)<span aria-hidden="true">*</span>@endif
  </label>
@endif

<div class="flex flex-col gap-1">
  <div class="file-upload-wrapper">
    <input
      id="{{ $inputId }}"
      name="{{ $inputName }}"
      type="file"
      @if($isRequired) aria-required="true" required @endif
      @if($failed) aria-invalid="true" @endif
      aria-describedby="{{ $ariaDescId }}"
      class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary/20 file:text-primary hover:file:bg-primary/30 border rounded @if($failed) border-red-500 @else border-gray-300 @endif"
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
    <div id="{{ $ariaDescId }}" class="text-sm text-gray-600">{{ $description }}</div>
  @endif

  @if($failed && $message)
    <div class="text-sm text-red-600">{{ wp_strip_all_tags($message) }}</div>
  @endif
</div>
