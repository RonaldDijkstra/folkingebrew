<x-section :classes="'bg-center bg-no-repeat bg-cover'" :backgroundImage="$backgroundImage">
  <div class="absolute inset-0 bg-[#5C2D0C]/80 z-10"></div>
  <x-container :classes="'max-w-xl z-20 text-white relative'">
    <h2 class="text-2xl md:text-3xl font-bold mb-3 text-center font-sans">{{ $title }}</h2>
    <p class="mb-8 text-center text-lg font-sans">{{ $subtitle }}</p>
    @foreach ($events as $event)
      <div class="pb-3 sm:pb-2 mb-3 sm:flex justify-between w-full border border-b border-0 border-white">
        <div class="text-lg w-32 shrink-0 font-sans">{{ $event['date'] }}</div>
        <div class="flex-1 mb-2 sm:mb-3">
          <h3 class="text-lg font-bold font-sans m-0">{{ $event['title'] }}</h3>
          @if ($event['description'])
            <p class="text-white text-sm m-0 font-sans">{{ $event['description'] }}</p>
          @endif
        </div>
        @if($event['link'])
          <a href="{{ $event['link']['url'] }}" class="text-white underline hover:no-underline font-sans" target="{{ $event['link']['target'] }}">{{ $event['link']['title'] }}</a>
        @endif
      </div>
    @endforeach
  </x-container>
</x-section>
