@php
  $inputType = 'tel';

  // Handle autocomplete attribute - check for phoneFormat or other autocomplete-related properties
  $autocompleteValue = 'tel'; // Default fallback

  // Gravity Forms may use different property names, check common ones
  if (property_exists($field, 'phoneFormat') && !empty($field->phoneFormat)) {
    // Map phone formats to appropriate autocomplete values
    switch ($field->phoneFormat) {
      case 'domestic':
        $autocompleteValue = 'tel-national';
        break;
      case 'international':
        $autocompleteValue = 'tel';
        break;
      default:
        $autocompleteValue = $field->phoneFormat;
        break;
    }
  } elseif (property_exists($field, 'autocomplete') && !empty($field->autocomplete)) {
    $autocompleteValue = $field->autocomplete;
  }
@endphp

@if($label !== '')
  <label class="font-medium" for="{{ $inputId }}">
    {{ $label }} @if($isRequired)<span aria-hidden="true">*</span>@endif
  </label>
@endif


<div class="flex flex-col gap-1">
  <input
    id="{{ $inputId }}"
    name="{{ $inputName }}"
    type="{{ $inputType }}"
    value="{{ is_string($value) ? $value : '' }}"
    autocomplete="{{ $autocompleteValue }}"
    pattern="[0-9\s\-\+\(\)\.x#]+"
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($isRequired) aria-required="true" required @endif
    @if($failed) aria-invalid="true" @endif
    aria-describedby="{{ $ariaDescId }}"
    class="border rounded px-3 py-2 @if($size === 'small') w-1/3 @elseif($size === 'medium') w-1/2 @else w-full @endif @if($failed) border-red-500 @else border-gray-300 @endif"
    oninput="this.value = this.value.replace(/[^0-9\s\-\+\(\)\.x#]/g, '')"
  />

  @if($description)
    <div id="{{ $ariaDescId }}" class="text-sm text-gray-600">{{ $description }}</div>
  @endif

  @if($failed && $message)
    <div class="text-sm text-red-600">{{ wp_strip_all_tags($message) }}</div>
  @endif
</div>
