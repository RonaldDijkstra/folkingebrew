@props([
  'backgroundColor' => null,
  'classes' => null,
  'backgroundImage' => null,
])

@php($class = match ($backgroundColor) {
  'bg-white' => 'bg-white',
  'bg-neutral-light-brown' => 'bg-neutral-light-brown',
  default => '',
})

<section class="relative py-8 md:py-16 {{ $class }} {{ $backgroundColor }} {{ $classes }}" @if($backgroundImage && isset($backgroundImage['url'])) style="background-image: url({{ $backgroundImage['url'] }})"@endif>
  {{ $slot }}
</section>
