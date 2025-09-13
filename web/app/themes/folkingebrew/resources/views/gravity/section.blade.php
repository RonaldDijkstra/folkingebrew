@if($field->label)
  <h2 class="text-2xl font-bold mb-1">{{ $field->label }}</h2>
@endif
@if($field->description)
  <p class="text-body">{{ $field->description }}</p>
@endif
