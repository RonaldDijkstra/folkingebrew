@php
  $count = (function () {
    if (function_exists('WC') && WC() && WC()->cart) {
      return WC()->cart->get_cart_contents_count();
    }
    return 0;
  })();
@endphp

<a href="{{ wc_get_cart_url() }}"
   class="relative inline-flex items-center gap-1 text-white no-underline"
   aria-label="Open cart ({{ $count }} items)">
  {{-- Bag icon (simple SVG) --}}
  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M16 11V7a4 4 0 10-8 0v4M5 9h14l-1 11H6L5 9z"/>
  </svg>

  {{-- Count (this exact selector is what Woo will replace via fragments) --}}
  <span class="text-sm leading-none" id="header-cart-count" data-cart-count>
    {{ $count }}
  </span>
</a>
