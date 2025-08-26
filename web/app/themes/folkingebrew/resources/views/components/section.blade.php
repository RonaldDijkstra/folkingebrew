@props([
  'backgroundColor' => null,
])

@php($class = match ($backgroundColor) {
  'bg-white' => 'bg-white',
  'bg-neutral-light-brown' => 'bg-neutral-light-brown',
  default => 'bg-white',
})

<section class="{{ $class }} {{ $backgroundColor }} py-8 md:py-16">
  {{ $slot }}
</section>
