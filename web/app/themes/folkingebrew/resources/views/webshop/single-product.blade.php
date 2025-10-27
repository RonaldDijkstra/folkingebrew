@extends('layouts.app')

@section('content')

<x-container>
  <x-breadcrumb :items="$breadcrumbs ?? []" classes="pt-4" />
  {!! wc_print_notices() !!}
</x-container>

<x-container classes="pb-16">

  <div id="product-{{ $product->get_id() }}" {{ wc_product_class('grid grid-cols-1 lg:grid-cols-2 gap-8', $product) }} @if($isVariable) data-product-variations='@json($availableVariations)' @endif>
    <div class="space-y-4">
      <div class="bg-white overflow-hidden product-main-image">
        {!! $mainImageId ? wp_get_attachment_image($mainImageId, 'large', false, ['class' => 'w-full h-auto']) : wc_placeholder_img('large') !!}
      </div>

      @if(!empty($galleryIds))
        <div class="grid grid-cols-5 gap-2">
          @foreach($galleryIds as $imgId)
            @php
              $fullImage = wp_get_attachment_image_src($imgId, 'large');
              $fullSrc = $fullImage ? $fullImage[0] : '';
              $fullSrcset = wp_get_attachment_image_srcset($imgId, 'large');
            @endphp
            <button type="button" class="product-gallery-thumb bg-white rounded-lg overflow-hidden transition-all hover:ring-1 hover:ring-primary cursor-pointer">
              {!! wp_get_attachment_image($imgId, 'thumbnail', false, [
                'class' => 'w-full h-auto',
                'data-full-src' => $fullSrc,
                'data-full-srcset' => $fullSrcset
              ]) !!}
            </button>
          @endforeach
        </div>
      @endif
    </div>

    <div class="space-y-6">
      <div>
        <h1 class="text-2xl uppercase font-bold mb-2">{{ $product->get_name() }}</h1>
        @if($product->get_attribute('style') && $product->get_attribute('style') !== '')
          <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-3 py-1 rounded-md text-sm">
            <span>{{ $product->get_attribute('style') }}</span>
          </span>
        @endif
        @if($product->get_attribute('abv') && $product->get_attribute('abv') !== '')
          <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-3 py-1 rounded-md text-sm">
            <span>{{ $product->get_attribute('abv') }}%</span>
          </span>
        @endif
        <div class="mt-2 text-xl font-bold" id="product-price">
          @if($isBeer && $singleVariantPrice)
            {{-- Show only the SINGLE variant price for beer products --}}
            {!! $singleVariantPrice !!}
          @else
            {{-- Show default price for non-beer or simple products --}}
            {!! $product->get_price_html() !!}
          @endif
        </div>
      </div>

      {{-- Add to cart form --}}
      <div>
        @if($product->is_purchasable() && $product->is_in_stock())
          <form class="cart" action="{{ esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())) }}" method="post" enctype='multipart/form-data'>
            @php do_action('woocommerce_before_add_to_cart_button'); @endphp

            {{-- Variation selector for variable products --}}
            @if($isVariable && !empty($variationAttributes))
              <div class="space-y-4 mb-6">
                @foreach($variationAttributes as $attributeKey => $attribute)
                  <div>
                    <label class="block text-sm font-medium mb-2 capitalize">
                      {{ $attribute['name'] }}
                    </label>
                    <div class="flex flex-wrap gap-2">
                      @foreach($attribute['options'] as $option)
                        <button
                          type="button"
                          class="variation-option px-4 py-2 border border-gray-300 hover:border-black transition-colors rounded"
                          data-attribute="{{ $attributeKey }}"
                          data-value="{{ esc_attr($option) }}"
                        >
                          {{ esc_html($option) }}
                        </button>
                      @endforeach
                    </div>
                  </div>
                @endforeach
              </div>
              <input type="hidden" name="variation_id" id="variation_id" value="" />
              @foreach($variationAttributes as $attributeKey => $attribute)
                <input type="hidden" name="{{ $attributeKey }}" class="variation-attribute" value="" />
              @endforeach
            @endif

             <div class="flex items-center gap-4 product-actions-wrapper-{{ $product->get_id() }}">
               @if(!$product->is_sold_individually())
                 <div class="quantity flex items-center border border-gray-300 rounded">
                   <label class="sr-only" for="quantity">{{ __('Quantity', 'woocommerce') }}</label>
                   <button type="button" class="qty-btn minus px-4 py-2 text-gray-600 hover:text-black" aria-label="Decrease quantity">−</button>
                   <input
                     type="number"
                     id="quantity"
                     class="w-16 text-center border-x border-gray-300 py-2 appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                     name="quantity"
                     value="1"
                     min="1"
                     max="{{ $product->get_max_purchase_quantity() }}"
                     step="1"
                   />
                   <button type="button" class="qty-btn plus px-4 py-2 text-gray-600 hover:text-black" aria-label="Increase quantity">+</button>
                 </div>
               @endif

              {{-- Add to cart button --}}
              <button
                type="submit"
                name="add-to-cart"
                value="{{ esc_attr($product->get_id()) }}"
                class="flex-1 bg-black text-white px-6 py-2 border-black border hover:bg-gray-800 transition-colors font-medium ajax_add_to_cart"
                data-product_id="{{ $product->get_id() }}"
              >
                {{ $product->single_add_to_cart_text() }}
              </button>
            </div>

            @php do_action('woocommerce_after_add_to_cart_button'); @endphp
          </form>
        @else
          <p class="text-gray-500">{{ __('This product is currently unavailable.', 'folkingebrew') }}</p>
        @endif
      </div>

      @if($description)
        <div class="mt-6">
          <div class="prose max-w-none">{!! wpautop($description) !!}</div>
        </div>
      @endif

      @if($product->get_attribute('product-details') && $product->get_attribute('product-details') !== '')
        <div class="mt-6">
          <h2 class="text-lg font-bold mb-2">{{ __('Product details', 'folkingebrew') }}</h2>
          <div class="prose max-w-none">{!! wpautop($product->get_attribute('product-details')) !!}</div>
        </div>
      @endif

      {{-- Meta (SKU, cats, tags) --}}
      {{-- <div class="text-sm text-gray-600 space-y-1">
        @if(wc_product_sku_enabled() && $product->get_sku())
          <div><span class="font-medium">SKU:</span> {{ $product->get_sku() }}</div>
        @endif
        <div>{!! wc_get_product_category_list($product->get_id(), ', ', '<span class="font-medium">Categories:</span> ', '') !!}</div>
        <div>{!! wc_get_product_tag_list($product->get_id(), ', ', '<span class="font-medium">Tags:</span> ', '') !!}</div>
      </div> --}}
    </div>
  </div>

  @include('webshop.related-products')

  <meta itemprop="url" content="{{ get_permalink($product->get_id()) }}" />
</x-container>
@endsection
