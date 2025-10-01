<label class=" @if(isset($classes)) {{ $classes }} @endif font-medium" for="{{ $inputId }}">
  {{ $label }} @if($isRequired)<span aria-hidden="true" class="text-red-600">*</span>@endif
</label>
