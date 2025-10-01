<label class="flex items-center space-x-2 cursor-pointer">
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
  <div class="w-5 h-5 rounded border border-gray-400 flex items-center justify-center peer-checked:border-primary peer-checked:bg-primary transition">
  {{-- @TODO: Make this an icon instead  --}}
    <svg
      class="w-3 h-3 text-white peer-checked:block"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
      stroke="currentColor"
      stroke-width="3"
    >
      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
    </svg>
  </div>
  @include('gravity.label', [
    'label' => $label,
    'isRequired' => $isRequired,
    'failed' => $failed,
    'description' => $description,
    'classes' => 'font-normal',
  ])
</label>
