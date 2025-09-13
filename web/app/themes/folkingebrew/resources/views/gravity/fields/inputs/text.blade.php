<input
  type="{{ $type }}"
  id="{{ $id }}"
  name="{{ $name }}"
  value="{{ $value }}"
  placeholder="{{ $placeholder }}"
  class="border rounded px-3 py-2 @if(isset($classes)) {{ $classes }} @endif @if($failed) border-red-500 @else border-gray-300 @endif"
  @if($isRequired) required @endif
/>
