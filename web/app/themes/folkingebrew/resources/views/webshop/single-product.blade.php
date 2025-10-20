@extends('layouts.app')

@section('content')
  <x-container>
    <x-breadcrumb :items="$breadcrumbs ?? []" classes="pt-4" />
  </x-container>
  <x-container classes="py-16">
    <div class="flex flex-col sm:flex-row gap-12 sm:mb-8">
      <div class="w-full sm:w-1/2">
        <figure class="block mb-0">
          <picture class="block w-full max-w-4xl m-auto">
            @if($product->get_image())
              {!! $product->get_image('full', ['class' => 'w-full h-auto object-cover']) !!}
            @endif
          </picture>
        </figure>
      </div>
      <div class="w-full sm:w-1/2">
        <h1 class="text-2xl sm:text-3xl font-bold text-body text-left uppercase mb-3">
          {{ $product->get_title() }}
        </h1>
        @if($attributes)
          <div class="flex flex-wrap gap-2">
            @foreach($attributes as $attribute)
              @if(in_array(strtolower($attribute['name']), ['style', 'abv']))
                <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-3 py-1 rounded-md text-sm">
                  <span class="font-semibold">{{ $attribute['name'] }}:</span>
                  <span>{{ $attribute['value'] }}</span>
                </span>
              @endif
            @endforeach
          </div>
        @endif
        <div class="flex items-center gap-3 my-3">
          <div class="text-primary font-bold text-xl">
            {!! $product->get_price_html() !!}
          </div>
          @if($discountPercentage)
            <span class="inline-block bg-red-600 text-white px-3 py-1 rounded-md text-sm font-semibold">
              -{{ $discountPercentage }}%
            </span>
          @endif
        </div>

        @if($product->is_purchasable() && $product->is_in_stock())
          <form class="cart" action="{{ esc_url(wc_get_cart_url()) }}" method="post" enctype="multipart/form-data">
            <div class="flex items-end gap-4 mb-4">
              <div class="flex flex-col">
                <label for="quantity" class="text-sm font-medium text-body mb-2">
                  {{ __('Quantity', 'folkingebrew') }}
                </label>
                <input
                  type="number"
                  id="quantity"
                  name="quantity"
                  value="1"
                  min="1"
                  max="{{ $product->get_max_purchase_quantity() }}"
                  class="w-20 px-3 py-2 border border-gray-300  focus:outline-none focus:ring-2 focus:ring-primary"
                />
              </div>
              <button
                type="submit"
                name="add-to-cart"
                value="{{ $product->get_id() }}"
                class="bg-primary hover:bg-primary-dark text-white font-semibold px-6 py-2 cursor-pointer transition-colors"
              >
                {{ __('Add to Cart', 'folkingebrew') }}
              </button>
            </div>
          </form>
        @elseif(!$product->is_in_stock())
          <p class="text-red-600 font-medium">
            {{ __('Out of stock', 'folkingebrew') }}
          </p>
        @endif
      </div>
    </div>
  </x-container>
@endsection
