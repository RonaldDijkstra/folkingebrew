@php
  // Determine time format from field properties
  $timeFormat = $field->timeFormat ?? '12'; // Default to 12-hour format
  $is12Hour = $timeFormat === '12';

  // Parse existing value if it exists - handle multiple value formats like date fields
  $valuesBySuffix = ['1' => '', '2' => '', '3' => ''];

  if (is_array($value)) {
    // Support both keyed and numeric arrays
    $valuesBySuffix['1'] = (string) ($value['hour'] ?? ($value['1'] ?? ($value[0] ?? '')));
    $valuesBySuffix['2'] = (string) ($value['minute'] ?? ($value['2'] ?? ($value[1] ?? '')));
    $valuesBySuffix['3'] = (string) ($value['ampm'] ?? ($value['3'] ?? ($value[2] ?? '')));
  } elseif (is_string($value) && !empty($value)) {
    // Parse time string like "14:30" or "2:30 PM"
    if (preg_match('/^(\d{1,2}):(\d{2})\s*(AM|PM)?$/i', $value, $matches)) {
      $valuesBySuffix['1'] = $matches[1];
      $valuesBySuffix['2'] = $matches[2];
      $valuesBySuffix['3'] = $matches[3] ?? '';
    }
  }

  // Fallback to POST when available (similar to date fields)
  foreach (['1','2','3'] as $suf) {
    if ($valuesBySuffix[$suf] === '') {
      $postKey = 'input_' . $fieldId . '_' . $suf;
      $posted = function_exists('rgpost') ? rgpost($postKey) : ($_POST[$postKey] ?? '');
      if (is_string($posted) && $posted !== '') {
        $valuesBySuffix[$suf] = $posted;
      }
    }
  }

  // Get sub-field labels from field inputs or use fallbacks
  $hourLabel = __('Hour', 'folkingebrew');
  $minuteLabel = __('Minute', 'folkingebrew');
  $ampmLabel = __('AM/PM', 'folkingebrew');

  if (property_exists($field, 'inputs') && is_array($field->inputs)) {
    foreach ($field->inputs as $input) {
      if (!empty($input) && is_array($input)) {
        $inputId = $input['id'] ?? '';
        // Use customLabel if available, otherwise fall back to label
        $labelToUse = $input['customLabel'] ?? $input['label'] ?? '';

        if (str_ends_with($inputId, '.1') && !empty($labelToUse)) {
          $hourLabel = $labelToUse;
        } elseif (str_ends_with($inputId, '.2') && !empty($labelToUse)) {
          $minuteLabel = $labelToUse;
        } elseif (str_ends_with($inputId, '.3') && !empty($labelToUse)) {
          $ampmLabel = $labelToUse;
        }
      }
    }
  }
@endphp

@if($label !== '')
  <label class="font-medium">
    {{ $label }} @if($isRequired)<span aria-hidden="true">*</span>@endif
  </label>
@endif

<div class="time-field flex flex-col gap-1">
  <div class="flex items-center gap-2">
    {{-- Hour Input --}}
    <div class="flex flex-col">
      <label for="{{ $inputId }}_1" class="text-xs text-gray-600 mb-1">
        {{ $hourLabel }}
      </label>
      <input
        id="{{ $inputId }}_1"
        name="{{ $inputName }}_1"
        type="number"
        value="{{ $valuesBySuffix['1'] }}"
        placeholder="{{ $is12Hour ? '12' : '00' }}"
        min="{{ $is12Hour ? '1' : '0' }}"
        max="{{ $is12Hour ? '12' : '23' }}"
        step="1"
        @if($isRequired) aria-required="true" required @endif
        @if($failed) aria-invalid="true" @endif
        aria-describedby="{{ $ariaDescId }}"
        class="border rounded px-3 py-2 w-20 text-center @if($failed) border-red-500 @else border-gray-300 @endif"
      />
    </div>

    {{-- Separator --}}
    <div class="text-xl font-medium mt-6">:</div>

    {{-- Minute Input --}}
    <div class="flex flex-col">
      <label for="{{ $inputId }}_2" class="text-xs text-gray-600 mb-1">
        {{ $minuteLabel }}
      </label>
      <input
        id="{{ $inputId }}_2"
        name="{{ $inputName }}_2"
        type="number"
        value="{{ $valuesBySuffix['2'] }}"
        placeholder="00"
        min="0"
        max="59"
        step="1"
        @if($isRequired) aria-required="true" required @endif
        @if($failed) aria-invalid="true" @endif
        aria-describedby="{{ $ariaDescId }}"
        class="border rounded px-3 py-2 w-20 text-center @if($failed) border-red-500 @else border-gray-300 @endif"
      />
    </div>

    {{-- AM/PM Dropdown (only for 12-hour format) --}}
    @if($is12Hour)
      <div class="flex flex-col">
        <label for="{{ $inputId }}_3" class="text-xs text-gray-600 mb-1">
          {{ $ampmLabel }}
        </label>
        <select
          id="{{ $inputId }}_3"
          name="{{ $inputName }}_3"
          @if($isRequired) aria-required="true" required @endif
          @if($failed) aria-invalid="true" @endif
          aria-describedby="{{ $ariaDescId }}"
          class="border rounded px-3 py-2 @if($failed) border-red-500 @else border-gray-300 @endif"
        >
           <option value="" disabled {{ empty($valuesBySuffix['3']) ? 'selected' : '' }}>{{ __('Select', 'folkingebrew') }}</option>
          <option value="AM" {{ $valuesBySuffix['3'] === 'AM' ? 'selected' : '' }}>AM</option>
          <option value="PM" {{ $valuesBySuffix['3'] === 'PM' ? 'selected' : '' }}>PM</option>
        </select>
      </div>
    @endif
  </div>

  @if($description)
    <div id="{{ $ariaDescId }}" class="text-sm text-gray-600">{{ $description }}</div>
  @endif

  @if($failed && $message)
    <div class="text-sm text-red-600">{{ wp_strip_all_tags($message) }}</div>
  @endif
</div>
