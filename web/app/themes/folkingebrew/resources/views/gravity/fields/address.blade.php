@php
  // Guards
  $rawInputs = (property_exists($field, 'inputs') && is_array($field->inputs)) ? $field->inputs : [];
  $ariaDescId = $ariaDescId ?? "desc_{$fieldId}";
  $isRequired = !empty($isRequired);
  $failed = !empty($failed);
  $message = $message ?? '';
  $description = $description ?? '';

  // Helpers
  $toArr = function ($in) {
    if (is_object($in)) {
      return [
        'id'          => (string)($in->id ?? ''),
        'label'       => $in->label ?? '',
        'placeholder' => $in->placeholder ?? '',
        'isHidden'    => (bool)($in->isHidden ?? false),
      ];
    }
    if (is_array($in)) {
      return [
        'id'          => (string)($in['id'] ?? ''),
        'label'       => $in['label'] ?? '',
        'placeholder' => $in['placeholder'] ?? '',
        'isHidden'    => (bool)($in['isHidden'] ?? false),
      ];
    }
    return ['id' => '', 'label' => '', 'placeholder' => '', 'isHidden' => false];
  };

  $suffix = function (string $id) use ($fieldId) {
    // Address sub-inputs are like "<fieldId>.<n>"
    $parts = explode('.', $id);
    return (count($parts) === 2 && (string)$parts[0] === (string)$fieldId) ? (string)$parts[1] : '';
  };

  $typeMap = [
    '1' => ['key' => 'street', 'placeholder' => 'Street Address', 'full' => true],
    '2' => ['key' => 'address2', 'placeholder' => 'Address Line 2', 'full' => true],
    '3' => ['key' => 'city', 'placeholder' => 'City', 'full' => false],
    '4' => ['key' => 'state', 'placeholder' => 'State / Province / Region', 'full' => false],
    '5' => ['key' => 'zip', 'placeholder' => 'ZIP / Postal Code', 'full' => false],
    '6' => ['key' => 'country', 'placeholder' => 'Country', 'full' => false],
  ];

  // Normalize and enrich inputs
  $inputs = [];
  foreach ($rawInputs as $ri) {
    $i = $toArr($ri);
    if (empty($i['id']) || $i['isHidden']) continue;

    $suf = $suffix($i['id']);
    if (!isset($typeMap[$suf])) continue;

    $meta = $typeMap[$suf];
    $type = $meta['key'];
    $placeholder = $i['placeholder'] ?: $meta['placeholder'];

    // Current value
    $currentValue = '';
    if (is_array($value) && array_key_exists($i['id'], $value)) {
      $currentValue = $value[$i['id']];
    }

    $inputs[] = [
      'id'          => $i['id'],
      'domId'       => "input_{$fieldId}_{$i['id']}",
      'name'        => "input_{$i['id']}",
      'label'       => $i['label'],
      'placeholder' => $placeholder,
      'type'        => $type,
      'isFull'      => $meta['full'],
      'value'       => $currentValue,
    ];
  }

  // Split groups and enforce display order
  $orderFull = ['street', 'address2'];
  $orderHalf = ['city', 'state', 'zip', 'country'];

  $full = array_values(array_filter($inputs, fn($i) => $i['isFull']));
  usort($full, fn($a, $b) => array_search($a['type'], $orderFull) <=> array_search($b['type'], $orderFull));

  $half = array_values(array_filter($inputs, fn($i) => !$i['isFull']));
  usort($half, fn($a, $b) => array_search($a['type'], $orderHalf) <=> array_search($b['type'], $orderHalf));

  // Optional: render country as select if we pass $countries = ['NL' => 'Netherlands', ...]
  $countries = $countries ?? null;
  $renderCountryAsSelect = is_array($countries) && count($countries) > 0;

  $inputBase = 'border rounded px-3 py-2 w-full';
  $inputBorder = $failed ? 'border-red-500' : 'border-gray-300';
@endphp

@if(!empty($label ?? ''))
  <label class="font-medium">
    {{ $label }} @if($isRequired)<span aria-hidden="true">*</span>@endif
  </label>
@endif

<div class="flex flex-col gap-1">
  <div class="flex flex-col gap-3">
    {{-- Full width: street + address2 --}}
    @foreach($full as $i)
      <div class="w-full">
        @if(!empty($i['label']))
          <label class="block text-xs text-gray-600 mb-1" for="{{ $i['domId'] }}">{{ $i['label'] }}</label>
        @endif

        <input
          type="text"
          id="{{ $i['domId'] }}"
          name="{{ $i['name'] }}"
          value="{{ $i['value'] }}"
          placeholder="{{ $i['placeholder'] }}"
          @if($isRequired && $i['type'] === 'street') aria-required="true" @endif
          @if($failed) aria-invalid="true" @endif
          aria-describedby="{{ $ariaDescId }}"
          class="{{ $inputBase }} {{ $inputBorder }}"
        />

      </div>
    @endforeach

    {{-- Half width: grid of 2 --}}
    @if(count($half))
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        @foreach($half as $i)
          <div class="w-full">
            @if(!empty($i['label']))
              <label class="block text-xs text-gray-600 mb-1" for="{{ $i['domId'] }}">{{ $i['label'] }}</label>
            @endif

            @if($i['type'] === 'country' && $renderCountryAsSelect)
               <select
                 id="{{ $i['domId'] }}"
                 name="{{ $i['name'] }}"
                 @if($isRequired && $i['type'] === 'city') aria-required="true" @endif
                 @if($failed) aria-invalid="true" @endif
                 aria-describedby="{{ $ariaDescId }}"
                 class="{{ $inputBase }} {{ $inputBorder }}"
               >
                 <option value="" disabled @selected(empty($i['value']))>{{ $i['placeholder'] }}</option>
                   @foreach($countries as $code => $name)
                     <option value="{{ $name }}" @selected((string)$i['value'] === (string)$code || (string)$i['value'] === (string)$name)>{{ $name }}</option>
                   @endforeach
               </select>
            @else
              <input
                type="text"
                id="{{ $i['domId'] }}"
                name="{{ $i['name'] }}"
                value="{{ $i['value'] }}"
                placeholder="{{ $i['placeholder'] }}"
                @if($isRequired && $i['type'] === 'city') aria-required="true" @endif
                @if($failed) aria-invalid="true" @endif
                aria-describedby="{{ $ariaDescId }}"
                class="{{ $inputBase }} {{ $inputBorder }}"
              />
            @endif
          </div>
        @endforeach
      </div>
    @endif
  </div>

  @if(!empty($description))
    <div id="{{ $ariaDescId }}" class="text-sm text-gray-600">{{ $description }}</div>
  @endif

  @if($failed && !empty($message))
    <div class="text-sm text-red-600">{{ wp_strip_all_tags($message) }}</div>
  @endif
</div>
