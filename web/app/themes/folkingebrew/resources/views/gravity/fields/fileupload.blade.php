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

{{-- Single-file --}}
@if(empty($multipleFiles))
  <input
    type="file"
    id="{{ $inputId }}"
    name="{{ $inputName }}"
    @if($isRequired) aria-required="true" required @endif
    @if($failed) aria-invalid="true" @endif
    aria-describedby="{{ $ariaDescId }}"
    class="block w-full text-sm text-body file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary/20 file:text-primary hover:file:bg-primary/30 border rounded @if($failed) border-red-500 @else border-gray-300 @endif"
    @if(isset($field) && property_exists($field, 'allowedExtensions') && $field->allowedExtensions) accept="{{ $field->allowedExtensions }}" @endif
  />
  @if(!empty($maxFileSize))
    <div>{{ __("Max file size", "folkingebrew") }}: {{ $maxFileSizeMB }} MB</div>
  @endif
  @if($allowedRaw !== '*')
    <div>{{ __("Allowed types", "folkingebrew") }}: {{ $allowedRaw }}</div>
  @endif
@endif

{{-- Multi-file --}}
@if(!empty($multipleFiles))
  <div id="{{ $fieldElementId }}">
    <div>
      <div
        id="{{ $multiWrapId }}"
        data-settings="{{ $settingsJson }}"
        class="gform_fileupload_multifile"
      >
        <div id="{{ $dropAreaId }}" class="border-2 border-dashed border-primary rounded-md p-10 flex flex-col items-center justify-center mb-2 @if($failed) border-red-500 @endif">
          <div class="text-md mb-2">{{ __("Drop files here or", "folkingebrew") }}</div>
          <button type="button" id="{{ $browseBtnId }}" class="bg-primary text-white hover:bg-primary/80 cursor-pointer text-md px-3 py-1 no-underline">{{ __("Select files", "folkingebrew") }}</button>
        </div>
      </div>

      <div id="{{ $rulesId }}" class="text-sm mb-2">
        {{ __("Max file size", "folkingebrew") }}: {{ $maxFileSizeMB }} MB
        @if($maxFiles > 0)<br>{{ __("Max files", "folkingebrew") }}: {{ $maxFiles }}@endif
        @if($allowedRaw !== '*')<br>
          {{ __("Allowed types", "folkingebrew") }}: {{ $allowedRaw }}
        @endif
      </div>

      <ul id="{{ $messagesId }}" class="!list-none prose prose-li:pl-0 !mt-0 empty:hidden"></ul>
    </div>

    <div id="{{ $previewId }}"></div>
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
