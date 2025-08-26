@props([
  'type' => null,
  'link' => null,
])

@php($class = match ($type) {
  'primary' => 'bg-primary text-white hover:bg-primary/70',
  'outline' => 'bg-transparent text-white border border-white hover:bg-primary/10',
  'outline-primary' => 'bg-transparent text-primary border border-primary hover:bg-gray-100',
  default => 'bg-primary text-white hover:bg-primary/80',
})

<a href="{{ $link['url'] }}" class="{{ $class }} inline-block text-lg md:text-xl px-4 py-3 no-underline">{{ $link['title'] }}</a>
