<div class="text-center relative">
  <a href="{{ $product['permalink'] }}" class="no-underline">
    @if($product['sale'])
      <div class="absolute bg-primary text-white text-sm font-normal py-1 px-2 top-4 right-4">{{ __('SALE', 'folkingebrew') }}</div>
    @elseif($product['new'])
      <div class="absolute bg-black text-white text-sm font-normal py-1 px-2 top-4 right-4">{{ __('NEW', 'folkingebrew') }}</div>
    @endif
    @if($product['thumbnail'])
      <div class="figure w-full h-auto overflow-hidden mb-3">
        {!! $product['thumbnail'] !!}
      </div>
    @endif
    <h3 class="text-xl text-body font-bold pb-0">
      {{ $product['title'] }}
    </h3>
  </a>

  @if($product['abv'] || $product['style'] || $product['deposit'])
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
      @if($product['deposit'] && ($product['abv'] || $product['style']))
        <span class="mx-1">|</span>
      @endif
      @if($product['deposit'])
        <span>{{ __('Deposit', 'folkingebrew') }}: {!! $product['deposit'] !!}</span>
      @endif
    </div>
  @endif

  @if($product['price'])
    <div class="text-body text-lg font-bold mt-2">
      {!! $product['price'] !!}
    </div>
  @endif

  @if($product['is_purchasable'] && $product['is_in_stock'])
    <div class="mt-4 flex justify-center gap-2 items-center product-actions-wrapper-{{ $product['id'] }}">
      @if($product['product_type'] === 'variable' && $product['is_beer'] && $product['single_variant_id'])
        {{-- Beer products with SINGLE variant: Add SINGLE variant directly to cart --}}
        <form class="inline-block beer-variant-cart-form" method="post" enctype="multipart/form-data">
          <input type="hidden" name="add-to-cart" value="{{ $product['id'] }}" />
          <input type="hidden" name="product_id" value="{{ $product['id'] }}" />
          <input type="hidden" name="variation_id" value="{{ $product['single_variant_id'] }}" />
          <input type="hidden" name="quantity" value="1" />

          @if(!empty($product['single_variant_attributes']))
            @foreach($product['single_variant_attributes'] as $attrKey => $attrValue)
              <input type="hidden" name="{{ esc_attr($attrKey) }}" value="{{ esc_attr($attrValue) }}" />
            @endforeach
          @endif

          <button
            type="submit"
            class="button bg-black hover:bg-black/80 text-white font-normal py-2 px-6 transition-colors duration-200 inline-block no-underline text-lg cursor-pointer border-0"
            data-product_id="{{ $product['id'] }}"
            data-variation_id="{{ $product['single_variant_id'] }}"
          >
            {{ __('Add to cart', 'folkingebrew') }}
          </button>
        </form>
      @elseif($product['product_type'] === 'variable')
        {{-- Other variable products: Show select options --}}
        <a
          href="{{ $product['permalink'] }}"
          class="button bg-black hover:bg-black/80 text-white font-normal py-2 px-6 transition-colors duration-200 inline-block no-underline text-lg"
        >
          {{ __('Select options', 'folkingebrew') }}
        </a>
      @else
        {{-- Simple products: Add to cart --}}
        <a
          href="{{ $product['add_to_cart_url'] }}"
          data-quantity="1"
          data-product_id="{{ $product['id'] }}"
          data-product_sku=""
          class="button add_to_cart_button ajax_add_to_cart bg-black hover:bg-black/80 text-white font-normal py-2 px-6 transition-colors duration-200 inline-block no-underline text-lg"
          rel="nofollow"
        >
          {{ __('Add to cart', 'folkingebrew') }}
        </a>
      @endif
    </div>
  @elseif(!$product['is_in_stock'])
    <div class="mt-4">
      <span class="text-sm text-body/60">
        {{ __('Out of stock', 'folkingebrew') }}
      </span>
    </div>
  @endif
</div>
