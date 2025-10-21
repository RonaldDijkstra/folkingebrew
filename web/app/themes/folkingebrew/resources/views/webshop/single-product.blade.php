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
          <div id="add-to-cart-error" class="hidden mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
            <p class="font-medium p-0 m-0">
              {{ __('Cannot add to cart. You may have reached the stock limit for this size.', 'folkingebrew') }}
              <a href="{{ wc_get_cart_url() }}" class="underline font-semibold text-red-700 hover:text-red-900">
                {{ __('Check your cart', 'folkingebrew') }}
              </a>
              {{ __('to adjust quantities.', 'folkingebrew') }}
            </p>
          </div>

          <div id="add-to-cart-message" class="hidden mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
            <p class="font-medium p-0 m-0">
              {{ __('Successfully added to cart.', 'folkingebrew') }}
              <a href="{{ wc_get_cart_url() }}" class="underline font-semibold hover:text-green-900">
                {{ __('View cart', 'folkingebrew') }}
              </a>
            </p>
          </div>

          <form class="cart" method="post" enctype="multipart/form-data" id="add-to-cart-form" data-is-variable="{{ $isVariable ? 'true' : 'false' }}" data-product-id="{{ $product->get_id() }}" data-variations="{{ json_encode($variations) }}">
            <div class="flex items-end gap-4 mb-4">
              @if($isVariable && $variations)
                <div class="flex flex-col">
                  <label for="product-size" class="text-sm font-medium text-body mb-2">
                    {{ __('Size', 'folkingebrew') }}
                  </label>
                  <select
                    id="product-size"
                    name="attribute_pa_size"
                    class="px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary"
                  >
                    <option value="" disabled selected>{{ __('Choose size', 'folkingebrew') }}</option>
                    @foreach($variations as $variation)
                      @php
                        $sizeAttr = $variation['attributes']['attribute_pa_size'] ?? $variation['attributes']['attribute_size'] ?? '';
                        $stockText = $variation['is_in_stock'] ? '' : ' (' . __('Out of stock', 'folkingebrew') . ')';
                        $maxQty = $variation['stock_quantity'] ?? 9999;
                        if (!$variation['is_in_stock']) {
                          $maxQty = 0;
                        }
                      @endphp
                      <option
                        value="{{ $sizeAttr }}"
                        data-variation-id="{{ $variation['variation_id'] }}"
                        data-in-stock="{{ $variation['is_in_stock'] ? 'true' : 'false' }}"
                        data-max-quantity="{{ $maxQty }}"
                        data-price="{{ $variation['price'] }}"
                        data-regular-price="{{ $variation['regular_price'] }}"
                        data-sale-price="{{ $variation['sale_price'] }}"
                        {{ !$variation['is_in_stock'] ? 'disabled' : '' }}
                      >
                        {{ ucfirst($sizeAttr) }}{{ $stockText }}
                      </option>
                    @endforeach
                  </select>

                </div>
              @endif

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
                  class="w-20 px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary"
                />
              </div>
              <button
                type="submit"
                name="add-to-cart"
                value="{{ $product->get_id() }}"
                class="bg-primary hover:bg-primary-dark text-white font-semibold px-6 py-2 cursor-pointer transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                id="add-to-cart-button"
                {{ $isVariable ? 'disabled' : '' }}
              >
                {{ __('Add to Cart', 'folkingebrew') }}
              </button>
            </div>
            <div id="stock-info" class="text-xs text-body/60 hidden"></div>
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
