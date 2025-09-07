@extends('layouts.app')

@section('content')

<x-container>
  <div class="w-full text-center py-16">
    <h1 class="text-4xl font-normal text-white">{!! $title !!}</h1>
  </div>
</x-container>
<section>
  <x-container>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-9 max-w-7xl m-auto px-6 mb-12">

      @foreach($beers as $beer)
        <a href="{{ $beer->url }}" class="bg-black relative no-underline">
          @if($beer->image && isset($beer->image['url']) && !empty($beer->image['url']))
            <img src="{{ $beer->image['url'] }}" alt="{{ $beer->image['alt'] }}" class="w-full h-full object-cover">
          @endif
          <div class="transition-all opacity-0 w-full h-full bg-black absolute top-0 left-0 flex flex-col justify-center text-center p-10 hover:opacity-100 motion-reduce:transition-none motion-reduce:transform-none">
            <h2 class="text-white text-xl font-bold mb-2">{{ $beer->post_title }}</h2>
            <p class="text-lg  text-primary">{{ $beer->style }}</p>
          </div>
        </a>
      @endforeach
    </div>
    @if($numberOfPages > 1)
      <div class="mt-8">
        {{-- @include('components.pagination', ['numPages' => $numberOfPages, 'paged' => $paged]) --}}

        <x-pagination :numberOfPages="$numberOfPages" :paged="$paged" />
      </div>
    @endif
  </x-container>
</section>
@endsection
