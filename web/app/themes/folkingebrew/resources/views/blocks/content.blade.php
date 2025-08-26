<section class="bg-white py-16">
  <x-container :classes="'grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-16'">
    <div class="order-1 {{ $textRight ? 'md:order-2' : 'md:order-1' }} md:flex md:flex-col md:justify-center">
      <h2 class="text-2xl md:text-3xl font-bold mb-3">{!! $title !!}</h2>
      <div class="text-body mb-6">
        {!! $text !!}
      </div>
      <div class="self-start">
        <x-button :link="$link" type="outline-primary" />
      </div>
    </div>
    <div class="order-2 {{ $textRight ? 'md:order-1' : 'md:order-2' }}">
      <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" class="w-full h-auto">
    </div>
  </x-container>
</section>
