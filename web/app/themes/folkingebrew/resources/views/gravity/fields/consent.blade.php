
<div class="flex items-baseline gap-2">
  @if($label !== '')
    <label class="font-medium">
      {{ $label }} @if($isRequired)<span class="text-alert" aria-hidden="true">*</span>@endif
    </label>
  @endif
</div>

<div class="flex mb-2">
  <input
    id="{{ $inputId }}"
    name="input_{{ $fieldId }}.1"
    type="checkbox"
    value="1"
    @if($value == '1' || (is_array($value) && isset($value[$fieldId . '.1']) && $value[$fieldId . '.1'] == '1')) checked @endif
    @if($isRequired) aria-required="true" required @endif
    @if($failed) aria-invalid="true" @endif
    @if($description) aria-describedby="gfield_consent_description_{{ $formId }}_{{ $fieldId }}" @endif
    class="@if($failed) border-red-500 @else border-gray-300 @endif mr-3"
  />

  <label for="{{ $inputId }}" class="{{ $failed ? 'before:border-alert' : 'before:border-body'; }}">
    {!! $checkboxLabel !!}
  </label>

  {{-- Hidden inputs to store consent label and revision ID --}}
  <input type="hidden" name="input_{{ $fieldId }}.2" value="{{ $checkboxLabel }}" class="gform_hidden" />
  <input type="hidden" name="input_{{ $fieldId }}.3" value="{{ $consentVersion }}" class="gform_hidden" />
</div>
@if($failed && $message)
  <div class="text-sm text-red-600 mb-3">{{ wp_strip_all_tags($message) }}</div>
@endif
{{-- Consent description/terms if provided --}}
@if($description)
  <div id="gfield_consent_description_{{ $formId }}_{{ $fieldId }}" class="border border-inactive text-sm bg-gray-50 rounded overflow-auto max-h-48">
    <div class="max-h-50 p-5">
      {!! nl2br(e($description)) !!}
      <div class="h-5"></div>
    </div>
  </div>
@endif




