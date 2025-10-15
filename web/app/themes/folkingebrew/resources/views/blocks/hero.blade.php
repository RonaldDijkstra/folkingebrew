<div class="{{ $backgroundType === 'primary' ? 'bg-blend-soft-light bg-primary' : ''}} px-6 text-center @if(!$is_preview)-mt-16 sm:-mt-20 @endif bg-center bg-no-repeat bg-cover h-screen flex flex-col justify-center items-center" style="background-image: url({{ $backgroundImage['url'] ?? '' }})"
>
  @if ($backgroundType === 'pub')
    <div class="absolute inset-0 bg-[#5C2D0C]/80 has-adminbar:min-h-screen-plus-admin-bar z-10"></div>
  @endif
  <div class="relative mb-6 z-20 flex flex-col items-center">
    @if ($image && isset($image['url']) && !empty($image['url']))
      <div class="mb-6">
        <img src="{{ $image['url'] }}" alt="{{ $image['alt'] ?? '' }}" class="w-[240px] xs:w-[320px] md:w-[480px] h-auto">
      </div>
    @endif
    @if ($title)
      <h1 class="text-white text-3xl md:text-4xl font-light text-center font-sans m-0">
        {{ $title }}
      </h1>
    @endif
  </div>
  <div class="flex flex-col xs:flex-row gap-4 z-20">
    @foreach ($buttons as $button)
      <x-button :type="$button['type']" :link="$button['link']" />
    @endforeach
  </div>
</div>
