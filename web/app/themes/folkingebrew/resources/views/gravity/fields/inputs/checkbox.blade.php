<label class="flex items-center space-x-2 cursor-pointer">
  <div class="relative">
    <input
      type="checkbox"
      name="{{ $name }}"
      value="{{ $value }}"
      class="hidden peer @if(isset($classes)) {{ $classes }} @endif"
      @if($checked) checked @endif
      @if($isRequired) aria-required="true" @endif
      @if($failed) aria-invalid="true" @endif
      @if(isset($dataChoiceIndex)) data-choice-index="{{ $dataChoiceIndex }}" @endif
    />
    <div class="w-5 h-5 bg-white rounded border border-gray-400 flex items-center justify-center peer-checked:border-primary peer-checked:bg-primary transition">
    </div>
    <x-icon name="checkmark" classes="w-3 h-3 text-white absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 hidden peer-checked:block" />
  </div>
  <span class="text-body">{{ $label }}</span>
</label>
