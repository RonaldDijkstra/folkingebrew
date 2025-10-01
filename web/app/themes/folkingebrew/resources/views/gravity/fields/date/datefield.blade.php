<div class="grid grid-cols-3 gap-2" data-datefield="true" @if($noPastDates) data-no-past-dates="true" @endif data-root-id="input_{{ $formId }}_{{ $fieldId }}">
  @foreach($processedInputs as $input)
    <div class="flex flex-col gap-1">
      <label class="text-sm font-medium text-gray-700 block mb-1" for="{{ $input['subId'] }}">{{ $input['labelText'] }}</label>
      <input
        id="{{ $input['subId'] }}"
        name="{{ $input['subName'] }}"
        type="text"
        inputmode="numeric"
        pattern="{{ $input['pattern'] }}"
        maxlength="{{ $input['maxlength'] }}"
        placeholder="{{ $input['placeholderText'] }}"
        value="{{ $input['current'] }}"
        @if($isRequired) aria-required="true" required @endif
        @if($failed) aria-invalid="true" @endif
        aria-describedby="{{ $ariaDescId }}"
        class="border rounded px-3 py-2 w-full @if($failed) border-red-500 @else border-gray-300 @endif"
        data-part="{{ $input['part'] }}"
        @if($noPastDates) data-no-past-dates="true" @endif
      />
    </div>
  @endforeach
</div>
