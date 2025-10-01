<div class="grid grid-cols-3 gap-2" data-datedropdown="true" @if($noPastDates) data-no-past-dates="true" @endif data-root-id="input_{{ $formId }}_{{ $fieldId }}">
  @foreach($processedInputs as $input)
    <div class="flex flex-col gap-1">
      <label class="text-sm font-medium text-gray-700 block mb-1" for="{{ $input['subId'] }}">{{ $input['placeholderText'] }}</label>

      @if($input['part'] === 'm')
        <select id="{{ $input['subId'] }}" name="{{ $input['subName'] }}" class="border rounded px-3 py-2 w-full @if($failed) border-red-500 @else border-gray-300 @endif" @if($isRequired) aria-required="true" required @endif @if($failed) aria-invalid="true" @endif aria-describedby="{{ $ariaDescId }}">
          <option value="" disabled @if($input['current']==='') selected @endif>{{ __('Select', 'folkingebrew') }}</option>
          @foreach($months as $m)
            @php $val = str_pad((string) $m, 2, '0', STR_PAD_LEFT); @endphp
            <option value="{{ $val }}" @if($input['current'] === $val) selected @endif>{{ $val }}</option>
          @endforeach
        </select>
      @elseif($input['part'] === 'd')
        <select id="{{ $input['subId'] }}" name="{{ $input['subName'] }}" class="border rounded px-3 py-2 w-full @if($failed) border-red-500 @else border-gray-300 @endif" @if($isRequired) aria-required="true" required @endif @if($failed) aria-invalid="true" @endif aria-describedby="{{ $ariaDescId }}">
          <option value="" disabled @if($input['current']==='') selected @endif>{{ __('Select', 'folkingebrew') }}</option>
          @for($d=1; $d<=$daysMax; $d++)
            @php $val = str_pad((string) $d, 2, '0', STR_PAD_LEFT); @endphp
            <option value="{{ $val }}" @if($input['current'] === $val) selected @endif>{{ $val }}</option>
          @endfor
        </select>
      @else
        <select id="{{ $input['subId'] }}" name="{{ $input['subName'] }}" class="border rounded px-3 py-2 w-full @if($failed) border-red-500 @else border-gray-300 @endif" @if($isRequired) aria-required="true" required @endif @if($failed) aria-invalid="true" @endif aria-describedby="{{ $ariaDescId }}">
          <option value="" disabled @if($input['current']==='') selected @endif>{{ __('Select', 'folkingebrew') }}</option>
          @foreach($years as $y)
            <option value="{{ $y }}" @if($input['current'] == $y) selected @endif>{{ $y }}</option>
          @endforeach
        </select>
      @endif
    </div>
  @endforeach
</div>
