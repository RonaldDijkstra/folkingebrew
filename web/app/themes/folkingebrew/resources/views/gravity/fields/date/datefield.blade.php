@php
  $formId = (int) ($field->formId ?? 0);
  $fieldId = (int) ($field->id ?? 0);
  $inputs = is_array($field->inputs ?? null) ? $field->inputs : [];

  // Map sub-input suffix to labels (Gravity Forms uses .1 = Month, .2 = Day, .3 = Year)
  $labelsBySuffix = [
    '1' => '', // Month
    '2' => '', // Day
    '3' => '', // Year
  ];

  foreach ($inputs as $input) {
    $parts = explode('.', (string) ($input['id'] ?? ''));
    $suffix = $parts[1] ?? null;
    if ($suffix && isset($labelsBySuffix[$suffix])) {
      $labelsBySuffix[$suffix] = (string) ((($input['customLabel'] ?? '') !== '') ? $input['customLabel'] : ($input['label'] ?? ''));
    }
  }

  // Determine display order from dateFormat (e.g., mdy, dmy, ymd, mdy_dash, etc.)
  $formatKey = explode('_', (string) ($field->dateFormat ?? 'mdy'))[0];
  $formatKey = in_array($formatKey, ['mdy', 'dmy', 'ymd'], true) ? $formatKey : 'mdy';
  $order = str_split($formatKey);

  // Map order char to GF suffix
  $map = [
    'm' => ['suffix' => '1', 'fallback' => __('Month', 'folkingebrew')],
    'd' => ['suffix' => '2', 'fallback' => __('Day', 'folkingebrew')],
    'y' => ['suffix' => '3', 'fallback' => __('Year', 'folkingebrew')],
  ];

  // Determine values per suffix: prefer provided $value (array), fallback to POST (rgpost/$_POST)
  $valuesBySuffix = ['1' => '', '2' => '', '3' => ''];
  if (is_array($value)) {
    // Support both keyed and numeric arrays
    $valuesBySuffix['1'] = (string) ($value['month'] ?? ($value['1'] ?? ''));
    $valuesBySuffix['2'] = (string) ($value['day'] ?? ($value['2'] ?? ''));
    $valuesBySuffix['3'] = (string) ($value['year'] ?? ($value['3'] ?? ''));
  }
  // Fallback to POST when available
  foreach (['1','2','3'] as $suf) {
    if ($valuesBySuffix[$suf] === '') {
      $postKey = 'input_' . $fieldId . '_' . $suf; // PHP converts dots to underscores
      $posted = function_exists('rgpost') ? rgpost($postKey) : ($_POST[$postKey] ?? '');
      if (is_string($posted) && $posted !== '') {
        $valuesBySuffix[$suf] = $posted;
      }
    }
  }
@endphp
<div class="grid grid-cols-3 gap-2" data-datefield="true" @if($noPastDates) data-no-past-dates="true" @endif data-root-id="input_{{ $formId }}_{{ $fieldId }}">
  @foreach($order as $part)
    @php
      $suffix = $map[$part]['suffix'];
      $subId = 'input_' . $formId . '_' . $fieldId . '_' . $suffix;
      $subName = 'input_' . $fieldId . '.' . $suffix;
      $subLabel = trim($labelsBySuffix[$suffix] ?: $map[$part]['fallback']);
      $maxlength = $part === 'y' ? 4 : 2;
      $pattern = $part === 'y' ? '\\d{4}' : '\\d{1,2}';
      $subValue = $valuesBySuffix[$suffix] ?? '';
    @endphp
    <div class="flex flex-col gap-1">
      <label class="text-sm font-medium text-gray-700 block mb-1" for="{{ $subId }}">{{ $subLabel }}</label>
      <input
        id="{{ $subId }}"
        name="{{ $subName }}"
        type="text"
        inputmode="numeric"
        pattern="{{ $pattern }}"
        maxlength="{{ $maxlength }}"
        placeholder="{{ $subLabel }}"
        value="{{ $subValue }}"
        @if($isRequired) aria-required="true" required @endif
        @if($failed) aria-invalid="true" @endif
        aria-describedby="{{ $ariaDescId }}"
        class="border rounded px-3 py-2 w-full @if($failed) border-red-500 @else border-gray-300 @endif"
        data-part="{{ $part }}"
        @if($noPastDates) data-no-past-dates="true" @endif
      />
    </div>
  @endforeach
</div>
