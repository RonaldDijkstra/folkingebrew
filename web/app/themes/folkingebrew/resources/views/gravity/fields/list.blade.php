@if($label !== '' && $field->labelPlacement !== 'hidden_label')
  @include('gravity.label', [
    'label' => $label,
    'isRequired' => $isRequired,
    'inputId' => $inputId,
  ])
@endif

<div class="list-field-container"
     data-field-id="{{ $fieldId }}"
     data-form-id="{{ $formId }}"
     data-max-rows="{{ $maxRows }}"
     data-enable-columns="{{ $enableColumns ? 'true' : 'false' }}"
     data-column-count="{{ $columnCount }}">

  {{-- Headers --}}
  @if($hasMultiColumns)
    <div class="list-headers grid gap-2 mb-2" style="grid-template-columns: repeat({{ $columnCount }}, 1fr) auto;">
      @foreach($columns as $column)
        <div class="font-medium text-sm text-gray-700">{{ $column['label'] }}</div>
      @endforeach
      <div class="w-16"></div>
    </div>
  @endif

  {{-- Rows --}}
  <div class="list-rows space-y-2">
    @foreach($existingValues as $rowIndex => $rowData)
      <div class="list-row grid gap-2 items-center"
           data-row-index="{{ $rowIndex }}"
           style="grid-template-columns: repeat({{ $columnCount }}, 1fr) auto;">

        @foreach($columns as $colIndex => $column)
          @php
            $inputId    = $makeInputId($rowIndex, $colIndex);
            $inputValue = (string) ($rowData[$colIndex] ?? '');
            $ariaLabel  = $makeAriaLabel($rowIndex, $column, $hasMultiColumns);
            $cellCls    = "{$cellBaseCls} gfield_list_{$fieldId}_cell" . ($colIndex + 1);
          @endphp

          <div class="{{ $cellCls }}" @if($hasMultiColumns) data-label="{{ $column['label'] }}" @endif>
            <input
              type="text"
              id="{{ $inputId }}"
              name="{{ $baseInputName }}"
              value="{{ $inputValue }}"
              aria-label="{{ $ariaLabel }}"
              data-aria-label-template="{{ $hasMultiColumns ? ($column['label'] . ', Row {0}') : 'Row {0}' }}"
              @if($isRequired && $rowIndex === 0 && $colIndex === 0) required @endif
              @if($failed) aria-invalid="true" @endif
              class="{{ $inputBaseCls }} {{ $inputBorder }} w-full"
            />
          </div>
        @endforeach

        <div class="list-row-controls flex gap-1">
          <button type="button"
                  class="add-row-btn bg-primary hover:bg-primary-dark text-white rounded-full w-8 h-8 flex items-center justify-center text-lg font-bold transition-colors cursor-pointer"
                  title="{{ __('Add Row', 'folkingebrew') }}"
                  @if($maxRows > 0) data-max-rows="{{ $maxRows }}" @endif>+</button>
          <button type="button"
                  class="remove-row-btn bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-lg font-bold transition-colors cursor-pointer"
                  title="{{ __('Remove Row', 'folkingebrew') }}">−</button>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Row count --}}
  @if($maxRows > 0)
    <div class="row-count-info text-sm text-gray-600 mt-2">
      <span class="current-row-count">{{ count($existingValues) }}</span> / {{ $maxRows }} {{ __('rows', 'folkingebrew') }}
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

{{-- Template for JS add-row --}}
<template id="list-row-template-{{ $fieldId }}" class="list-row-template">
  <div class="list-row grid gap-2 items-center"
       data-row-index="__ROW_INDEX__"
       style="grid-template-columns: repeat({{ $columnCount }}, 1fr) auto;">

    @foreach($columns as $colIndex => $column)
      @php
        $tplId     = "input_{$formId}_{$fieldId}___ROW_INDEX___{$colIndex}";
        $tplLabel  = $hasMultiColumns ? ($column['label'] . ', Row __ROW_NUMBER__') : 'Row __ROW_NUMBER__';
        $cellCls   = "{$cellBaseCls} gfield_list_{$fieldId}_cell" . ($colIndex + 1);
      @endphp

      <div class="{{ $cellCls }}" @if($hasMultiColumns) data-label="{{ $column['label'] }}" @endif>
        <input
          type="text"
          id="{{ $tplId }}"
          name="{{ $baseInputName }}"
          value=""
          aria-label="{{ $tplLabel }}"
          data-aria-label-template="{{ $hasMultiColumns ? ($column['label'] . ', Row {0}') : 'Row {0}' }}"
          class="{{ $inputBaseCls }} border-gray-300 w-full"
        />
      </div>
    @endforeach

    <div class="list-row-controls flex gap-1">
      <button type="button"
              class="add-row-btn cursor-pointer bg-primary hover:bg-primary-dark text-white rounded-full w-8 h-8 flex items-center justify-center text-lg font-bold transition-colors"
              title="{{ __('Add Row', 'folkingebrew') }}"
              @if($maxRows > 0) data-max-rows="{{ $maxRows }}" @endif>+</button>
      <button type="button"
              class="remove-row-btn cursor-pointer bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-lg font-bold transition-colors"
              title="{{ __('Remove Row', 'folkingebrew') }}">−</button>
    </div>
  </div>
</template>
