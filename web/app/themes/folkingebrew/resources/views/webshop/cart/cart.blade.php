@extends('layouts.app')

@section('content')

<x-container>

  @php do_action('woocommerce_before_cart'); @endphp

  <div class="flex flex-row gap-6">
    <div class="basis-2/3 min-w-0">
      <form class="woocommerce-cart-form block w-full p-0 m-0"
            action="{{ esc_url( wc_get_cart_url() ) }}"
            method="post">

        @php
          // Required nonce for cart updates
          wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce');
        @endphp

        <div class="flex flex-col gap-6"> {{-- A: wrapper --}}

          <div class="flex flex-col gap-4"> {{-- B: cart items --}}
            @foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
              @php
                /** @var \WC_Product $product */
                $product    = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                if (!$product || !$product->exists() || $cart_item['quantity'] <= 0) {
                  continue;
                }
              @endphp

              <div class="flex gap-4 border-b border-gray-200 pb-4 {{ esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)) }}">
                {{-- Thumbnail --}}
                <div class="flex-shrink-0">
                  @if ($product->get_image_id())
                    {!! wp_get_attachment_image($product->get_image_id(), 'thumbnail', false, ['class' => 'w-48 h-48 object-cover']) !!}
                  @else
                    {!! wc_placeholder_img('thumbnail', ['class' => 'w-24 h-24 object-cover rounded']) !!}
                  @endif
                </div>

                {{-- Content --}}
                <div class="flex flex-1 justify-between items-start gap-4 min-w-0">
                  <div class="flex flex-col gap-2 min-w-0">
                    {{-- Name --}}
                    <h3 class="font-semibold">
                      {!! apply_filters(
                        'woocommerce_cart_item_name',
                        $product->is_visible()
                          ? sprintf('<a href="%s" class="text-body no-underline hover:underline">%s</a>', esc_url($product->get_permalink()), esc_html($product->get_name()))
                          : esc_html($product->get_name()),
                        $cart_item,
                        $cart_item_key
                      ) !!}
                    </h3>

                    {{-- Meta / variations --}}
                    <div class="text-sm text-gray-600">
                      {!! wc_get_formatted_cart_item_data($cart_item) !!}
                    </div>

                    {{-- Quantity --}}
                    <div class="quantity">
                      @if ($product->is_sold_individually())
                        <span class="inline-block px-2 py-1 border rounded">1</span>
                        <input type="hidden" name="cart[{{ $cart_item_key }}][qty]" value="1" />
                      @else
                        {!! woocommerce_quantity_input(
                          [
                            'input_name'  => "cart[{$cart_item_key}][qty]",
                            'input_value' => $cart_item['quantity'],
                            'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                            'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                            'step'        => apply_filters('woocommerce_quantity_input_step', 1, $product),
                          ],
                          $product,
                          false
                        ) !!}
                      @endif
                    </div>

                    {{-- Remove --}}
                    <a href="{{ esc_url( wc_get_cart_remove_url($cart_item_key) ) }}"
                       class="text-sm text-red-600 hover:text-red-800"
                       aria-label="{{ esc_attr( sprintf( __('Remove %s from cart', 'folkingebrew'), $product->get_name() ) ) }}"
                       data-product_id="{{ esc_attr($product_id) }}"
                       data-cart_item_key="{{ esc_attr($cart_item_key) }}">
                      {{ __('Remove', 'folkingebrew') }}
                    </a>
                  </div>

                  {{-- Subtotal --}}
                  <div class="flex flex-col items-end gap-1 text-primary font-bold text-lg">
                    {!! apply_filters(
                      'woocommerce_cart_item_subtotal',
                      WC()->cart->get_product_subtotal($product, $cart_item['quantity']),
                      $cart_item,
                      $cart_item_key
                    ) !!}
                    @php
                      $line_subtotal = $cart_item['line_subtotal'];
                      $line_total    = $cart_item['line_total'];
                    @endphp
                    @if ($line_subtotal !== $line_total)
                      <p class="text-sm text-gray-500 line-through">{!! wc_price($line_subtotal) !!}</p>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div> {{-- /B --}}

          {{-- Optional update cart actions (kept commented) --}}
          <div class="flex justify-end gap-3">
            <button
              type="submit"
              name="update_cart"
              value="{{ esc_attr__('Update cart', 'woocommerce') }}"
              class="px-6 py-3 bg-gray-900 text-white rounded hover:bg-gray-800 transition"
            >
              {{ __('Update cart', 'woocommerce') }}
            </button>
          </div>


        </div> {{-- /A --}}

        @php do_action('woocommerce_after_cart_table'); @endphp
      </form>
    </div>

    <div class="basis-1/3 min-w-0">
      @php do_action('woocommerce_cart_collaterals'); @endphp
    </div>
  </div>

  @php do_action('woocommerce_after_cart'); @endphp
</x-container>

@endsection
