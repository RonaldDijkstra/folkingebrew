@php
  $formId = (int) ($field->formId ?? 0);
  $fieldId = (int) ($field->id ?? 0);
  $inputs = is_array($field->inputs ?? null) ? $field->inputs : [];

  // Build label and placeholder maps by suffix (.1 = month, .2 = day, .3 = year)
  $labelsBySuffix = ['1' => '', '2' => '', '3' => ''];
  $placeholdersBySuffix = ['1' => '', '2' => '', '3' => ''];
  foreach ($inputs as $input) {
    $parts = explode('.', (string) ($input['id'] ?? ''));
    $suffix = $parts[1] ?? null;
    if ($suffix && isset($labelsBySuffix[$suffix])) {
      $customLabel = (string) ($input['customLabel'] ?? '');
      $label = (string) ($input['label'] ?? '');
      $labelsBySuffix[$suffix] = $customLabel !== '' ? $customLabel : $label;

      // Gravity Forms stores sub-input placeholders under 'placeholder' (and sometimes 'placeholderValue')
      $ph = (string) ($input['placeholder'] ?? ($input['placeholderValue'] ?? ''));
      $placeholdersBySuffix[$suffix] = $ph !== '' ? $ph : $labelsBySuffix[$suffix];
    }
  }

  // Order: mdy/dmy/ymd (support *_dash variants)
  $formatKey = explode('_', (string) ($field->dateFormat ?? 'mdy'))[0];
  $formatKey = in_array($formatKey, ['mdy', 'dmy', 'ymd'], true) ? $formatKey : 'mdy';
  $order = str_split($formatKey);

  $map = [
    'm' => ['suffix' => '1', 'fallback' => __('Month', 'folkingebrew')],
    'd' => ['suffix' => '2', 'fallback' => __('Day', 'folkingebrew')],
    'y' => ['suffix' => '3', 'fallback' => __('Year', 'folkingebrew')],
  ];

  // Hydrate current values
  $valuesBySuffix = ['1' => '', '2' => '', '3' => ''];
  if (is_array($value)) {
    $valuesBySuffix['1'] = (string) ($value['month'] ?? ($value['1'] ?? ''));
    $valuesBySuffix['2'] = (string) ($value['day'] ?? ($value['2'] ?? ''));
    $valuesBySuffix['3'] = (string) ($value['year'] ?? ($value['3'] ?? ''));
  }
  foreach (['1','2','3'] as $suf) {
    if ($valuesBySuffix[$suf] === '') {
      $postKey = 'input_' . $fieldId . '_' . $suf;
      $posted = function_exists('rgpost') ? rgpost($postKey) : ($_POST[$postKey] ?? '');
      if (is_string($posted) && $posted !== '') {
        $valuesBySuffix[$suf] = $posted;
      }
    }
  }

  // Build options
  $months = range(1, 12);
  $years = range((int) date('Y'), (int) date('Y') + 10);
  $daysMax = 31; // client-side days list is generic; server validates exact date
@endphp

<div class="grid grid-cols-3 gap-2" data-datedropdown="true" @if($noPastDates) data-no-past-dates="true" @endif data-root-id="input_{{ $formId }}_{{ $fieldId }}">
  @foreach($order as $part)
    @php
      $suffix = $map[$part]['suffix'];
      $subId = 'input_' . $formId . '_' . $fieldId . '_' . $suffix;
      $subName = 'input_' . $fieldId . '_' . $suffix; // underscore for GF
      $fallback = $map[$part]['fallback'];
      $labelText = trim($labelsBySuffix[$suffix] ?: $fallback);
      $placeholderText = trim($placeholdersBySuffix[$suffix] ?: $labelText ?: $fallback);
      $current = $valuesBySuffix[$suffix] ?? '';
    @endphp
    <div class="flex flex-col gap-1">
      <label class="text-sm font-medium text-gray-700 block mb-1" for="{{ $subId }}">{{ $placeholderText }}</label>

      @if($part === 'm')
        <select id="{{ $subId }}" name="{{ $subName }}" class="border rounded px-3 py-2 w-full @if($failed) border-red-500 @else border-gray-300 @endif" @if($isRequired) aria-required="true" required @endif @if($failed) aria-invalid="true" @endif aria-describedby="{{ $ariaDescId }}">
          <option value="" disabled @if($current==='') selected @endif>{{ __('Select', 'folkingebrew') }}</option>
          @foreach($months as $m)
            @php $val = str_pad((string) $m, 2, '0', STR_PAD_LEFT); @endphp
            <option value="{{ $val }}" @if($current === $val) selected @endif>{{ $val }}</option>
          @endforeach
        </select>
      @elseif($part === 'd')
        <select id="{{ $subId }}" name="{{ $subName }}" class="border rounded px-3 py-2 w-full @if($failed) border-red-500 @else border-gray-300 @endif" @if($isRequired) aria-required="true" required @endif @if($failed) aria-invalid="true" @endif aria-describedby="{{ $ariaDescId }}">
          <option value="" disabled @if($current==='') selected @endif>{{ __('Select', 'folkingebrew') }}</option>
          @for($d=1; $d<=$daysMax; $d++)
            @php $val = str_pad((string) $d, 2, '0', STR_PAD_LEFT); @endphp
            <option value="{{ $val }}" @if($current === $val) selected @endif>{{ $val }}</option>
          @endfor
        </select>
      @else
        <select id="{{ $subId }}" name="{{ $subName }}" class="border rounded px-3 py-2 w-full @if($failed) border-red-500 @else border-gray-300 @endif" @if($isRequired) aria-required="true" required @endif @if($failed) aria-invalid="true" @endif aria-describedby="{{ $ariaDescId }}">
          <option value="" disabled @if($current==='') selected @endif>{{ __('Select', 'folkingebrew') }}</option>
          @foreach($years as $y)
            <option value="{{ $y }}" @if($current == $y) selected @endif>{{ $y }}</option>
          @endforeach
        </select>
      @endif
    </div>
  @endforeach
</div>
