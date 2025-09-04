@extends('layouts.app')

@section('content')

<section class="bg-transparent px-6 -mt-16 pt-16 md:-mt-20 md:pt-20 flex flex-col justify-center items-center bg-center bg-no-repeat bg-scroll bg-cover md:bg-fixed md:h-screen" style="background-image: url({{ $wallpaper['url'] }})">

  <figure>
    <picture class="block w-full max-w-4xl m-auto">
      @if($image && isset($image['url']) && !empty($image['url']))
        <img src="{{ $image['url'] }}" alt="{{ $image['alt'] ?? '' }}" class="w-full h-auto" width="800" height="800">
      @endif
    </picture>
  </figure>
</section>

<x-section backgroundColor="bg-white">
  <x-container :classes="'max-w-3xl'">
    <div class="flex flex-col sm:flex-row gap-4 sm:mb-8">
      <div class="w-full sm:border-r sm:pr-8 sm:border-gray-400">
        <h1 class="text-xl sm:text-3xl font-bold mb-2">{{ $title }}</h1>
        <p class="text-base text-gray-400 sm:text-lg font-medium mb-6">{{ $style }} | {{ $abv }}% </p>
        <p class="text-base sm:text-lg font-medium mb-6">
          {!! $description !!}
        </p>
      </div>
      <div class="sm:w-1/3 sm:pl-5">
        @if($specs && !empty($specs))
          @foreach($specs as $spec)
            <div class="text-base mb-6">
              <div class="font-bold">{{ $spec['key'] }}</div>
              <div class="font-normal">{{ $spec['value'] }}</div>
            </div>
          @endforeach
        @endif
        @if($untappd_link && isset($untappd_link['url']) && !empty($untappd_link['url']))
          <a href="{{ $untappd_link['url'] }}" target="_blank" class="inline-block p-1 text-base bg-primary hover:bg-primary-dark font-medium">
            <img src="{{ Vite::asset('resources/images/icon-untappd.svg') }}" alt="Untappd" class="w-5">
          </a>
        @endif
      </div>
    </div>
    @if($archiveLink)
      <div>
        <a href="{{ $archiveLink }}" class="inline-block p-1 text-lg text-white no-underline py-2 px-3 bg-primary hover:bg-primary-dark font-medium">
          {{ __('Back to beers', 'folkingebrew') }}
        </a>
      </div>
    @endif
  </x-container>
</x-section>

@endsection
