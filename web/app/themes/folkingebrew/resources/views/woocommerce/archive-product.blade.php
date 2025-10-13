@extends('layouts.app')

@section('content')
  <div class="py-8">
    <h1 class="text-3xl font-bold text-body text-center uppercase">
      {{ $title }}
    </h1>
  </div>

  <x-container>
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
          @else
            <div class="mt-8">
          @endif

              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach($categoryGroup['products'] as $product)
                  <div class="text-center">
                    <a href="{{ $product['permalink'] }}" class="no-underline">
                      @if($product['thumbnail'])
                        <div class="figure w-full h-auto overflow-hidden mb-3">
                          {!! $product['thumbnail'] !!}
                        </div>
                      @endif
                      <h3 class="text-xl text-body font-bold pb-0">
                        {{ $product['title'] }}
                      </h3>
                    </a>

                    @if($product['price'])
                      <div class="text-body text-lg text-primary font-bold mt-2">
                        {!! $product['price'] !!}
                      </div>
                    @endif

                    @if($product['abv'] || $product['style'])
                      <div class="text-sm text-body/80 mt-1">
                        @if($product['style'])
                          <span>{{ $product['style'] }}</span>
                        @endif
                        @if($product['abv'] && $product['style'])
                          <span class="mx-1">|</span>
                        @endif
                        @if($product['abv'])
                          <span>{{ $product['abv'] }}%</span>
                        @endif
                      </div>
                    @endif

                    @if($product['is_purchasable'] && $product['is_in_stock'])
                      <div class="mt-4 flex justify-center gap-2 items-center">
                        <a
                          href="{{ $product['add_to_cart_url'] }}"
                          data-quantity="1"
                          data-product_id="{{ $product['id'] }}"
                          data-product_sku=""
                          class="button add_to_cart_button ajax_add_to_cart bg-primary hover:bg-primary-dark text-white font-bold py-2 px-6 transition-colors duration-200 inline-block no-underline text-lg"
                          rel="nofollow"
                        >
                          {{ __('Add to cart', 'folkingebrew') }}
                        </a>
                      </div>
                    @elseif(!$product['is_in_stock'])
                      <div class="mt-4">
                        <span class="text-sm text-body/60">
                          {{ __('Out of stock', 'folkingebrew') }}
                        </span>
                      </div>
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
        @endif
      @endforeach
    @endif
  </x-container>
@endsection
