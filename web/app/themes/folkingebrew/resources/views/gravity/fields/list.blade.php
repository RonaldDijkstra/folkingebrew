@if($label !== '')
  <label class="font-medium block mb-2">
    {{ $label }} @if($isRequired)<span aria-hidden="true">*</span>@endif
  </label>
@endif

@php
  // Base config
  $formId         = (int) ($field->formId ?? 0);
  $fieldId        = (int) ($field->id ?? 0);
  $maxRows        = $maxRows ?? 0; // 0 means unlimited
  $enableColumns  = (bool) ($enableColumns ?? false);
  $choices        = is_array($choices ?? null) ? $choices : [];
  $listFields     = $listFields ?? [];
  $ariaDescId     = $ariaDescId ?? null;
  $failed         = !empty($failed);
  $message        = $message ?? '';
  $description    = $description ?? '';

  // Columns
  $hasChoices   = $enableColumns && !empty($choices);
  $columnCount  = $hasChoices ? count($choices) : 1;
  $columns      = $hasChoices
    ? array_values(array_map(
        fn($c, $i) => ['label' => $c['text'] ?? ($c['value'] ?? "Column " . ($i + 1)), 'index' => $i],
        $choices,
        array_keys($choices)
      ))
    : [['label' => '', 'index' => 0]];

  // Values: prefer saved value then POST fallback
  $existingValues = [];

  if (is_array($value) && !empty($value)) {
    // $value is: [row => ['COL_LABEL' => 'val', ...]]
    foreach ($value as $rowIndex => $rowData) {
      if (!is_array($rowData)) continue;
      $row = [];
      for ($i = 0; $i < $columnCount; $i++) {
        $key = $hasChoices ? ($choices[$i]['text'] ?? ($choices[$i]['value'] ?? '')) : $i;
        $row[$i] = (string) ($rowData[$key] ?? '');
      }
      $existingValues[$rowIndex] = $row;
    }
  }

  if (empty($existingValues) && function_exists('rgpost')) {
    // POST is a flat array: input_{fieldId}[] in row-major order
    $postValues = rgpost("input_{$fieldId}");
    if (is_array($postValues) && !empty($postValues)) {
      $valueIndex = 0;
      foreach ($postValues as $val) {
        $rowIndex = intdiv($valueIndex, $columnCount);
        $colIndex = $valueIndex % $columnCount;
        $existingValues[$rowIndex][$colIndex] = (string) $val;
        $valueIndex++;
      }
    }
  }

  // Ensure at least one empty row
  if (empty($existingValues)) {
    $existingValues = [ array_fill(0, $columnCount, '') ];
  }

  // Common helpers
  $baseInputName = "input_{$fieldId}[]";
  $inputBaseCls  = 'list-input border rounded px-3 py-2 focus:border-blue-500 focus:outline-none';
  $inputBorder   = $failed ? 'border-red-500' : 'border-gray-300';
  $cellBaseCls   = "gfield_list_group_item gfield_list_cell gform-grid-col";

  $makeInputId = function(int $row, int $col) use ($formId, $fieldId) {
    // Keep a predictable id pattern for accessibility and testing
    return "input_{$formId}_{$fieldId}_{$row}_{$col}";
  };

  $makeAriaLabel = function(int $row, array $column, bool $hasMulti) {
    return $hasMulti && !empty($column['label'])
      ? $column['label'] . ', Row ' . ($row + 1)
      : 'Row ' . ($row + 1);
  };

  $hasMultiColumns = $enableColumns && count($columns) > 1;
@endphp

<div class="list-field-container"
     data-field-id="{{ $fieldId }}"
     data-form-id="{{ $formId }}"
     data-max-rows="{{ $maxRows }}"
     data-enable-columns="{{ $enableColumns ? 'true' : 'false' }}"
     data-column-count="{{ count($columns) }}">

  {{-- Headers --}}
  @if($hasMultiColumns)
    <div class="list-headers grid gap-2 mb-2" style="grid-template-columns: repeat({{ count($columns) }}, 1fr) auto;">
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
           style="grid-template-columns: repeat({{ count($columns) }}, 1fr) auto;">

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
              class="{{ $inputBaseCls }} {{ $inputBorder }}"
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

  {{-- Description and error --}}
  @if($description)
    <p @if($ariaDescId) id="{{ $ariaDescId }}" @endif class="text-sm text-gray-600 mt-2">{{ $description }}</p>
  @endif

  @if($failed && $message)
    <p class="text-sm text-red-600 mt-2">{{ wp_strip_all_tags($message) }}</p>
  @endif
</div>

{{-- Template for JS add-row --}}
<template id="list-row-template-{{ $fieldId }}" class="list-row-template">
  <div class="list-row grid gap-2 items-center"
       data-row-index="__ROW_INDEX__"
       style="grid-template-columns: repeat({{ count($columns) }}, 1fr) auto;">

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
          class="{{ $inputBaseCls }} border-gray-300"
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
