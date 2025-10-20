@extends('layouts.app')

@section('content')
  <x-container>
    <x-breadcrumb :items="$breadcrumbs ?? []" classes="pt-4" />
    <h1 class="text-3xl font-bold text-body text-center uppercase">
      {{ $title }}
    </h1>
  </x-container>

  <x-container classes="pb-16">
    @if(empty($productsByCategory) || count($productsByCategory) === 0)
      <p class="text-center">
        {{ $notFoundText }}
      </p>
    @else
      @foreach($productsByCategory as $categoryGroup)
        @if(count($categoryGroup['products']) > 0)
          <div class="mt-12 first:mt-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
              @foreach($categoryGroup['products'] as $product)
                <x-card-product :product="$product" />
              @endforeach
            </div>
          </div>
        @endif
      @endforeach
    @endif
  </x-container>
@endsection
