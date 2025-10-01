@if($label !== '')
  <fieldset class="multiple-choice-field">
    @include('gravity.label', [
      'label' => $label,
      'isRequired' => $isRequired,
      'inputId' => $inputId,
    ])
  </fieldset>
@endif

<div class="flex flex-col gap-1 multiple-choice-container"
     data-field-id="{{ $fieldId }}"
     data-form-id="{{ $formId }}"
     data-select-mode="{{ $selectMode }}"
     data-exact-count="{{ $exactCount }}"
     data-min-range="{{ $minRange }}"
     data-max-range="{{ $maxRange }}"
     data-enable-select-all="{{ $enableSelectAll ? 'true' : 'false' }}"
     data-enable-other="{{ $enableOther ? 'true' : 'false' }}"
     data-select-all-text="{{ $selectAllText }}">

  {{-- Select All Button (for checkbox modes only) --}}
  @if($inputType === 'checkbox' && $enableSelectAll && $isMultipleSelection)
    <div class="select-all-container mb-2">
      <button type="button"
              class="select-all-btn bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded text-sm transition-colors"
              data-target="{{ $fieldIdentifier }}">
        {{ $selectAllText }}
      </button>
    </div>
  @endif

  <div class="space-y-2 choices-container" id="{{ $fieldIdentifier }}">
    @foreach($processedChoices as $choice)
      <div class="flex items-center gap-2 choice-item gchoice">
        @if($inputType === 'radio')
          @include('gravity.fields.inputs.radio', [
            'id' => $choice['id'],
            'label' => $choice['text'],
            'name' => $choice['name'],
            'value' => $choice['value'],
            'checked' => $choice['selected'],
            'isRequired' => false,
            'failed' => $failed,
            'classes' => 'choice-input',
            'data-choice-index' => $choice['index'],
          ])
        @elseif($inputType === 'checkbox')
         @include('gravity.fields.inputs.checkbox', [
           'id' => $choice['id'],
           'label' => $choice['text'],
           'name' => $choice['name'],
           'value' => $choice['value'],
           'checked' => $choice['selected'],
           'isRequired' => false,
           'failed' => $failed,
           'classes' => 'choice-input',
           'data-choice-index' => $choice['index'],
         ])
        @else
          <input
            type="{{ $inputType }}"
            id="{{ $choice['id'] }}"
            name="{{ $choice['name'] }}"
            value="{{ $choice['value'] }}"
            @if($choice['selected']) checked @endif
            @if($failed) aria-invalid="true" @endif
            class="choice-input @if($failed) border-red-500 @else border-gray-300 @endif"
            data-choice-index="{{ $choice['index'] }}"
          />
          <label for="{{ $choice['id'] }}" class="choice-label">
            {{ $choice['text'] }}
          </label>
        @endif
      </div>
    @endforeach

    {{-- Other Choice Input --}}
    @if($enableOther)
      <div class="flex items-center gap-2 choice-item other-choice gchoice">
        @if($inputType === 'radio')
          @include('gravity.fields.inputs.radio', [
            'id' => $otherChoice['choiceId'],
            'label' => $otherText,
            'name' => $otherChoice['subName'],
            'value' => 'gf_other_choice',
            'checked' => $otherChoice['selected'],
            'isRequired' => false,
            'failed' => $failed,
            'classes' => 'choice-input other-choice-input',
            'data-choice-index' => $otherChoice['choiceIndex'],
          ])
        @elseif($inputType === 'checkbox')
          @include('gravity.fields.inputs.checkbox', [
            'id' => $otherChoice['choiceId'],
            'label' => $otherText,
            'name' => $otherChoice['subName'],
            'value' => 'gf_other_choice',
            'checked' => $otherChoice['selected'],
            'isRequired' => false,
            'failed' => $failed,
            'classes' => 'choice-input other-choice-input',
            'data-choice-index' => $otherChoice['choiceIndex'],
          ])
        @else
          <input
            type="{{ $inputType }}"
            id="{{ $otherChoice['choiceId'] }}"
            name="{{ $otherChoice['subName'] }}"
            value="gf_other_choice"
            @if($otherChoice['selected']) checked @endif
            @if($failed) aria-invalid="true" @endif
            class="choice-input @if($failed) border-red-500 @else border-gray-300 @endif"
            data-choice-index="{{ $otherChoice['choiceIndex'] }}"
          />
          <label for="{{ $otherChoice['choiceId'] }}" class="choice-label">
            {{ $otherText }}
          </label>
        @endif
        <input
          type="text"
          id="{{ $otherChoice['textId'] }}"
          name="{{ $otherChoice['textName'] }}"
          value="{{ $otherChoice['textValue'] }}"
          placeholder="{{ __('Please specify...', 'folkingebrew') }}"
          class="other-text-input border rounded px-2 py-1 ml-2 @if($failed) border-red-500 @else border-gray-300 @endif @if(!$otherChoice['selected']) opacity-50 @endif"
          @if(!$otherChoice['selected']) disabled @endif
        />
      </div>
    @endif
  </div>

  {{-- Selection Information (below checkboxes) --}}
  {{-- Range Settings Display (for range mode) --}}
  @if($selectMode === 'range')
    <div class="range-info mt-2 text-sm text-gray-600">
      @if($minRange > 0 && $maxRange > 0)
        {{ sprintf(__('Select between %d and %d options', 'folkingebrew'), $minRange, $maxRange) }}
      @elseif($minRange > 0)
        {{ sprintf(__('Select at least %d options', 'folkingebrew'), $minRange) }}
      @elseif($maxRange > 0)
        {{ sprintf(__('Select up to %d options', 'folkingebrew'), $maxRange) }}
      @endif
    </div>
  @endif

  {{-- Exact Count Display (for exactly mode) --}}
  @if($selectMode === 'exactly')
    <div class="exact-info mt-2 text-sm text-gray-600">
      {{ sprintf(__('Select exactly %d options', 'folkingebrew'), $exactCount) }}
    </div>
  @endif


  {{-- Validation Messages --}}
  @if($selectMode === 'exactly')
    <div class="validation-message exactly-message text-sm text-red-600" style="display: none;">
      {{ sprintf(__('Please select exactly %d options.', 'folkingebrew'), $exactCount) }}
    </div>
  @endif

  @if($selectMode === 'range')
    <div class="validation-message range-message text-sm text-red-600" style="display: none;">
      @if($minRange > 0 && $maxRange > 0)
        {{ sprintf(__('Please select between %d and %d options.', 'folkingebrew'), $minRange, $maxRange) }}
      @elseif($minRange > 0)
        {{ sprintf(__('Please select at least %d options.', 'folkingebrew'), $minRange) }}
      @elseif($maxRange > 0)
        {{ sprintf(__('Please select up to %d options.', 'folkingebrew'), $maxRange) }}
      @endif
    </div>
  @endif

  @if($description)
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
</div>