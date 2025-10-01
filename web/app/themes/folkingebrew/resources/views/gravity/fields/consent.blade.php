<div class="flex mb-2">
  @include('gravity.fields.inputs.checkbox', [
    'id' => $inputId,
    'label' => $checkboxLabel,
    'name' => 'input_' . $fieldId . '.1',
    'value' => '1',
    'checked' => $value == '1' || (is_array($value) && isset($value[$fieldId . '.1']) && $value[$fieldId . '.1'] == '1'),
  ])

  <input type="hidden" name="input_{{ $fieldId }}.2" value="{{ $checkboxLabel }}" class="gform_hidden" />
  <input type="hidden" name="input_{{ $fieldId }}.3" value="{{ $consentVersion }}" class="gform_hidden" />
</div>
@if($failed && $message)
  <div class="text-sm text-red-600 mb-3">{{ wp_strip_all_tags($message) }}</div>
@endif
@if($description)
  <div id="gfield_consent_description_{{ $formId }}_{{ $fieldId }}" class="border border-inactive text-sm bg-gray-50 rounded overflow-auto max-h-48">
    <div class="max-h-50 p-5">
      {!! nl2br(e($description)) !!}
      <div class="h-5"></div>
    </div>
  </div>
@endif




