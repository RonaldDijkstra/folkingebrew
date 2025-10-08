@if($visibility === 'administrative') @php return; @endphp @endif

@php
  $inputBase = 'border rounded px-3 py-2 w-full';
  $inputBorder = $failed ? 'border-red-500' : 'border-gray-300';
@endphp

@if($label !== '' && $field->labelPlacement !== 'hidden_label')
  @include('gravity.label', [
    'label' => $label,
    'isRequired' => $isRequired,
    'inputId' => $inputId,
  ])
@endif

@if($description && $descriptionPlacement == 'above')
  @include('gravity.description', [
    'description' => $description,
    'ariaDescId' => $ariaDescId,
  ])
@endif

<div class="flex flex-col gap-1">
  <div class="flex flex-col gap-3">
    {{-- Full width fields --}}
    @foreach($full as $i)
      <div class="w-full">
        @if(!empty($i['label']))
          @include('gravity.label', [
            'label' => $i['label'],
            'isRequired' => $isRequired && $i['type'] === 'street',
            'inputId' => $i['domId'],
            'classes' => 'block text-sm mb-1',
          ])
        @endif

        @include('gravity.fields.inputs.text', [
          'type' => 'text',
          'id' => $i['domId'],
          'name' => $i['name'],
          'value' => $i['value'],
          'placeholder' => $i['placeholder'],
          'isRequired' => $isRequired && $i['type'] === 'street',
          'failed' => $failed,
          'ariaDescId' => $ariaDescId,
          'classes' => $inputBase . ' ' . $inputBorder,
        ])
      </div>
    @endforeach

    {{-- Half width: grid of 2 --}}
    @if(count($half))
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        @foreach($half as $i)
          <div class="w-full">
            @if(!empty($i['label']))
              @include('gravity.label', [
                'label' => $i['label'],
                'isRequired' => $isRequired && $i['type'] === 'street',
                'inputId' => $i['domId'],
                'classes' => 'block text-sm mb-1',
              ])
            @endif

            @if($i['type'] === 'country' && $renderCountryAsSelect)
               <select
                 id="{{ $i['domId'] }}"
                 name="{{ $i['name'] }}"
                 @if($isRequired && $i['type'] === 'city') aria-required="true" @endif
                 @if($failed) aria-invalid="true" @endif
                 aria-describedby="{{ $ariaDescId }}"
                 class="{{ $inputBase }} {{ $inputBorder }}">
                 <option value="" disabled @selected(empty($i['value']))>{{ $i['placeholder'] }}</option>
                  @foreach($countries as $code => $name)
                    <option value="{{ $name }}" @selected((string)$i['value'] === (string)$code || (string)$i['value'] === (string)$name)>{{ $name }}</option>
                  @endforeach
               </select>
            @else
              @include('gravity.fields.inputs.text', [
                'type' => 'text',
                'id' => $i['domId'],
                'name' => $i['name'],
                'value' => $i['value'],
                'placeholder' => $i['placeholder'],
                'isRequired' => $isRequired && $i['type'] === 'city',
                'failed' => $failed,
                'ariaDescId' => $ariaDescId,
                'classes' => $inputBase . ' ' . $inputBorder,
              ])
            @endif
          </div>
        @endforeach
      </div>
    @endif
  </div>

  @if($description && $descriptionPlacement != 'above')
    @include('gravity.description', [
      'description' => $description,
      'ariaDescId' => $ariaDescId,
    ])
  @endif

  @if($failed && !empty($message))
    @include('gravity.validation-field', [
      'message' => $message,
    ])
  @endif
</div>
