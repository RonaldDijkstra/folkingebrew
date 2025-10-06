@if(!empty($label))
  <label for="{{ $inputId }}">{{ $label }}@if(!empty($isRequired)) *@endif</label>
@endif

{{-- Single-file --}}
@if(empty($multipleFiles))
  <input
    type="file"
    id="{{ $inputId }}"
    name="{{ $inputName }}"
    @if(!empty($isRequired)) required @endif
    @if($accept !== '*') accept="{{ $accept }}" @endif
  />
  @if(!empty($maxFileSize))
    <div>Max file size: {{ $maxFileSizeMB }} MB</div>
  @endif
  @if($allowedRaw !== '*')
    <div>Allowed types: {{ $allowedRaw }}</div>
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
        <div id="{{ $dropAreaId }}">
          <span>Drop files here or </span>
          <button type="button" id="{{ $browseBtnId }}">Select files</button>
        </div>
      </div>

      <div id="{{ $rulesId }}">
        Max file size: {{ $maxFileSizeMB }} MB
        @if($maxFiles > 0). Max files: {{ $maxFiles }}@endif
      </div>

      <ul id="{{ $messagesId }}"></ul>
    </div>

    <div id="{{ $previewId }}"></div>
  </div>
@endif

@if(!empty($description))
  <div>{{ $description }}</div>
@endif

@if(!empty($failed) && !empty($message))
  <div>{{ $message }}</div>
@endif
