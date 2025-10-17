@props([
  'items' => [],
  'classes' => null,
])

<nav aria-label="Breadcrumb" class="mb-8 {{ $classes }}">
  <ol class="flex flex-wrap items-center gap-2 text-sm text-body">
    @foreach($items as $index => $item)
      @if($loop->last)
        <li class="flex items-center gap-2">
          <span class="font-medium" aria-current="page">{{ $item['text'] }}</span>
        </li>
      @else
        <li class="flex items-center gap-2">
          <a href="{{ $item['url'] }}" class="hover:underline transition-colors">
            {{ $item['text'] }}
          </a>
          <span class="text-body/50" aria-hidden="true">/</span>
        </li>
      @endif
    @endforeach
  </ol>
</nav>

