@extends('layouts.app')

@section('content')
  <x-container>
    <x-breadcrumb :items="$breadcrumbs ?? []" classes="pt-4" />
    <h1 class="text-3xl font-bold text-body text-center uppercase">
      {{ $title }}
    </h1>
  </x-container>

  <x-container classes="pb-16">
    @if(empty($productsByCategory))
      <p class="text-center text-white">
        {{ $notFoundText }}
      </p>
    @else
      @foreach($productsByCategory as $categoryGroup)
        @if(count($categoryGroup['products']) > 0)
          {{-- Only show category name as subtitle on the main shop page --}}
          @if($isShopPage)
            <div class="mt-12 first:mt-8">
              <h2 class="text-2xl font-semibold text-body mb-6">
                {{ $categoryGroup['category_name'] }}
              </h2>

              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach($categoryGroup['products'] as $product)
                  <x-card-product :product="$product" />
                @endforeach
              </div>
            </div>
          @endif
        @endif
      @endforeach
    @endif
  </x-container>
@endsection
