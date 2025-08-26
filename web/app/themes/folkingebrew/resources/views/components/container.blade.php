@props([
  'classes' => null,
])

<div class="container mx-auto px-4 {{ $classes }}">
  {{ $slot }}
</div>
