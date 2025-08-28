<x-section :backgroundColor="$backgroundColor">
  <x-container :classes="'grid grid-cols-1 gap-8 md:gap-16 ' . ($image ? 'md:grid-cols-2' : 'md:place-items-center')">
    <div class="order-1 {{ $textRight ? 'md:order-2' : 'md:order-1' }} md:flex md:flex-col md:justify-center {{ !$image ? 'md:max-w-2xl' : '' }}">
      @if ($title)
        <h2 class="text-2xl font-bold mb-2">{!! $title !!}</h2>
      @endif
      @if ($text)
        <div class="text-body mb-6 text-lg">
          {!! $text !!}
        </div>
      @endif
      @if ($link)
        <div class="self-start">
          <x-button :link="$link" type="outline-primary" />
        </div>
      @endif
    </div>
    @if ($image)
      <div class="order-2 {{ $textRight ? 'md:order-1' : 'md:order-2' }}">
        <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" class="w-full h-auto">
      </div>
    @endif
  </x-container>
</x-section>
