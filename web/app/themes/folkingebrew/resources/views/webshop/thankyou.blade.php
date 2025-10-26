<x-container classes="py-12">
  @if ($order)
    {{-- Order Confirmation Header --}}
    <div class="max-w-3xl mx-auto text-center mb-12">
      <div class="mb-6">
        <svg class="w-32 h-32 mx-auto text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>

      <h1 class="text-4xl font-normal mb-4">{{ __('Thank you for your order!', 'folkingebrew') }}</h1>

      @if ($order->has_status('failed'))
        <p class="text-xl text-red-600">
          {{ __('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce') }}
        </p>
      @else
        <p class="text-xl text-gray-600">
          {{ __('Your order has been received and is now being processed.', 'folkingebrew') }}
        </p>
      @endif
    </div>

    {{-- Order Details Summary --}}
    <div class="max-w-3xl mx-auto mb-8 py-4">
      <div class="bg-gray-100 rounded-lg p-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
          <p class="text-sm text-gray-600 uppercase mb-1">{{ __('Order number:', 'woocommerce') }}</p>
          <p class="font-bold text-lg">{{ $orderNumber }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-600 uppercase mb-1">{{ __('Date:', 'woocommerce') }}</p>
          <p class="font-bold text-lg">{{ $orderDate }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-600 uppercase mb-1">{{ __('Total:', 'woocommerce') }}</p>
          <p class="font-bold text-lg">{!! $orderTotal !!}</p>
        </div>
        <div>
          <p class="text-sm text-gray-600 uppercase mb-1">{{ __('Payment method:', 'woocommerce') }}</p>
          <p class="font-bold text-lg">{{ $paymentMethod }}</p>
        </div>
      </div>
    </div>

    {{-- Order Items --}}
    <div class="max-w-3xl mx-auto mb-8">
      <h2 class="text-2xl font-bold mb-4">{{ __('Order details', 'woocommerce') }}</h2>
      <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ __('Product', 'woocommerce') }}
              </th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ __('Total', 'woocommerce') }}
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            @foreach ($orderItems as $item)
              <tr>
                <td class="px-6 py-4">
                  <div>
                    <p class="font-medium">{{ $item['name'] }}</p>
                    <p class="text-sm text-gray-500">{{ __('Quantity:', 'woocommerce') }} {{ $item['quantity'] }}</p>
                  </div>
                </td>
                <td class="px-6 py-4 text-right font-medium">
                  {!! $item['total'] !!}
                </td>
              </tr>
            @endforeach
          </tbody>
          <tfoot class="bg-gray-50">
            @if ($order->get_subtotal() != $order->get_total())
              <tr>
                <td class="px-6 py-3 text-right font-medium">{{ __('Subtotal:', 'woocommerce') }}</td>
                <td class="px-6 py-3 text-right">{!! wc_price($order->get_subtotal()) !!}</td>
              </tr>
            @endif

            @if ($order->get_shipping_total() > 0)
              <tr>
                <td class="px-6 py-3 text-right font-medium">{{ __('Shipping:', 'woocommerce') }}</td>
                <td class="px-6 py-3 text-right">{!! wc_price($order->get_shipping_total()) !!}</td>
              </tr>
            @endif

            @if ($order->get_total_tax() > 0)
              <tr>
                <td class="px-6 py-3 text-right font-medium">{{ __('Tax:', 'woocommerce') }}</td>
                <td class="px-6 py-3 text-right">{!! wc_price($order->get_total_tax()) !!}</td>
              </tr>
            @endif

            <tr class="text-lg">
              <td class="px-6 py-4 text-right font-bold">{{ __('Total:', 'woocommerce') }}</td>
              <td class="px-6 py-4 text-right font-bold">{!! $orderTotal !!}</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    {{-- Customer Details --}}
    <div class="max-w-3xl mx-auto grid md:grid-cols-2 gap-8">
      {{-- Billing Address --}}
      <div>
        <h2 class="text-xl font-bold mb-4">{{ __('Billing address', 'woocommerce') }}</h2>
        <div class="bg-white border border-gray-200 rounded-lg p-6">
          {!! $billingAddress !!}
        </div>
      </div>

      {{-- Shipping Address --}}
      @if ($shippingAddress)
        <div>
          <h2 class="text-xl font-bold mb-4">{{ __('Shipping address', 'woocommerce') }}</h2>
          <div class="bg-white border border-gray-200 rounded-lg p-6">
            {!! $shippingAddress !!}
          </div>
        </div>
      @endif
    </div>

  @else
    {{-- No Order Found --}}
    <div class="max-w-2xl mx-auto text-center">
      <h1 class="text-4xl font-bold mb-4">{{ __('Order not found', 'folkingebrew') }}</h1>
      <p class="text-lg text-gray-600 mb-8">
        {{ __('We could not find your order. Please contact us if you have any questions.', 'folkingebrew') }}
      </p>
      <a href="{{ wc_get_page_permalink('shop') }}" class="inline-block bg-primary hover:bg-primary-dark text-white px-8 py-3 transition no-underline">
        {{ __('Return to shop', 'woocommerce') }}
      </a>
    </div>
  @endif
</x-container>
