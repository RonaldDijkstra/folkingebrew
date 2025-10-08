<input
  type="{{ $type }}"
  id="{{ $id }}"
  name="{{ $name }}"
  value="{{ $value }}"
  placeholder="{{ $placeholder }}"
  @if(($enableAutocomplete ?? true) && !empty($autocompleteAttribute)) autocomplete="{{ $autocompleteAttribute }}" @endif
  class="border rounded px-3 py-2 focus:outline-primary @if(isset($classes)) {{ $classes }} @endif @if($failed) border-red-500 @else border-gray-300 @endif"
  @if($isRequired) required @endif
/>
