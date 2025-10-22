<?php

namespace Custom\Shop\Settings;

use Custom\Shop\ServiceInterface;

class Settings implements ServiceInterface
{
    public function register(): void
    {
        // Disable WooCommerce's legacy CSS everywhere because it hurts our logos and other styles.
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');

        add_action('wp_footer', function () {
            if (!is_cart() && !is_checkout()) return;
            ?>
            <script>
            (function () {
              const selectors = [
                '.wc-block-components-sale-badge',
                '.wc-block-cart-item__sale-badge',
                '.wc-block-components-product-sale-badge'
              ];

              const removeBadges = (root = document) => {
                selectors.forEach(sel => root.querySelectorAll(sel).forEach(el => el.remove()));
              };

              // Initial pass (covers SSR markup)
              removeBadges();

              // Keep watching: blocks hydrate and re-render on updates
              const observer = new MutationObserver(mutations => {
                for (const m of mutations) {
                  if (m.type === 'childList') {
                    // Check added nodes directly and their descendants
                    m.addedNodes.forEach(node => {
                      if (node.nodeType !== 1) return;
                      if (selectors.some(sel => node.matches?.(sel))) node.remove();
                      removeBadges(node);
                    });
                  }
                }
              });

              observer.observe(document.body, { childList: true, subtree: true });

              // Also catch bfcache restores
              window.addEventListener('pageshow', removeBadges);

              // Optional: stop observing on SPA navigations away from cart/checkout
              document.addEventListener('woocommerce-route-changed', () => observer.disconnect());
            })();
            </script>
            <?php
        }, 100);

        add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
          if (! function_exists('WC') || ! WC()->cart) {
              $html = '<span class="text-sm leading-none" id="header-cart-count" data-cart-count>0</span>';
          } else {
              $count = WC()->cart->get_cart_contents_count();
              $html  = '<span class="text-sm leading-none" id="header-cart-count" data-cart-count>'. intval($count) .'</span>';
          }

          // Key must be a CSS selector that matches your element in the DOM.
          $fragments['span[data-cart-count]'] = $html;

          return $fragments;
      });

        // Restrict allowed countries to specific European countries
        add_filter('woocommerce_countries_allowed_countries', [$this, 'restrictAllowedCountries']);
        add_filter('woocommerce_countries_shipping_countries', [$this, 'restrictAllowedCountries']);

        add_action('woocommerce_init', function () {
            if (!function_exists('woocommerce_register_additional_checkout_field')) {
                return;
            }
            woocommerce_register_additional_checkout_field([
                'id'       => 'folkingebrew/subscribe-to-newsletter',
                'label'    => __('Subscribe to newsletter', 'folkingebrew'),
                'location' => 'order', // 'contact' | 'address' | 'order'
                'type'     => 'checkbox',  // 'text' | 'select' | 'checkbox'
                'required' => false,
            ]);
        });

        add_action('wp_enqueue_scripts', function () {
            // Example: if the floating-label script is named 'floating-labels'
            wp_dequeue_script('floating-labels');
        }, 20);
    }

    /**
     * Restrict WooCommerce countries to specific European countries
     *
     * @param array $countries All countries
     * @return array Filtered countries
     */
    public function restrictAllowedCountries(array $countries): array
    {
        $allowed_country_codes = [
            'NL' => true, // The Netherlands
            'AT' => true, // Austria
            'BE' => true, // Belgium
            'DK' => true, // Denmark
            'FR' => true, // France
            'DE' => true, // Germany
            'LU' => true, // Luxembourg
        ];

        return array_filter($countries, function ($country_code) use ($allowed_country_codes) {
            return isset($allowed_country_codes[$country_code]);
        }, ARRAY_FILTER_USE_KEY);
    }
}
