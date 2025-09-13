
<label class="flex items-center space-x-2 cursor-pointer">
  <input
    type="radio"
    name="{{ $name }}"
    value="{{ $value }}"
    class="hidden peer @if(isset($classes)) {{ $classes }} @endif"
    @if($checked) checked @endif
    @if($isRequired) aria-required="true" @endif
    @if($failed) aria-invalid="true" @endif
    @if(isset($dataChoiceIndex)) data-choice-index="{{ $dataChoiceIndex }}" @endif
  />
  <div class="w-5 h-5 rounded-full border border-gray-400 flex items-center justify-center peer-checked:border-primary peer-checked:bg-primary">
    <div class="w-2 h-2 bg-white rounded-full transition-opacity"></div>
  </div>
  <span class="text-body">{{ $label }}</span>
</label>
