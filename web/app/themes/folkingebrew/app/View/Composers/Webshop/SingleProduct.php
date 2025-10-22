<?php

namespace App\View\Composers\Webshop;

use Roots\Acorn\View\Composer;

class SingleProduct extends Composer
{
    protected static $views = [
        'webshop.single-product',
    ];

    public function with()
    {
        return [
            'product' => $this->getProduct(),
            'galleryIds' => $this->getGalleryIds(),
            'mainImageId' => $this->getMainImageId(),
            'breadcrumbs' => $this->getBreadcrumbs($this->getProduct()),
            'description' => $this->getDescription($this->getProduct()),
            'lowStockAmount' => $this->getLowStockAmount($this->getProduct()),
            'isVariable' => $this->isVariable(),
            'variationAttributes' => $this->getVariationAttributes(),
            'availableVariations' => $this->getAvailableVariations(),
        ];
    }

    /**
     * Get the product
     *
     * @return \WC_Product
     */
    protected function getProduct()
    {
        return wc_get_product(get_the_ID());
    }

    /**
     * Get the gallery image IDs
     *
     * @return array
     */
    protected function getGalleryIds()
    {
        return $this->getProduct()->get_gallery_image_ids();
    }

    /**
     * Get the main image ID
     *
     * @return int
     */
    protected function getMainImageId()
    {
        return $this->getProduct()->get_image_id();
    }

    private function getBreadcrumbs($product): array
    {
        $breadcrumbs = [
            [
                'text' => __('Home', 'folkingebrew'),
                'url'  => home_url('/'),
            ],
            [
                'text' => __('Webshop', 'folkingebrew'),
                'url'  => get_permalink(wc_get_page_id('shop')),
            ],
        ];

        // Get product categories
        $terms = get_the_terms(get_the_ID(), 'product_cat');

        if ($terms && !is_wp_error($terms)) {
            // Get the primary/first category (skip "uncategorized")
            foreach ($terms as $term) {
                if (strtolower($term->slug) !== 'uncategorized') {
                    $breadcrumbs[] = [
                        'text' => $term->name,
                        'url'  => get_term_link($term),
                    ];
                    break; // Only add the first relevant category
                }
            }
        }

        // Add product name as the last item (without URL to indicate current page)
        $breadcrumbs[] = [
            'text' => html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8'),
            'url'  => '',
        ];

        return $breadcrumbs;
    }

    private function getDescription($product): string
    {
        if (!$product) {
            return '';
        }

        return $product->get_description();
    }

    private function getLowStockAmount($product): int
    {
        return wc_get_low_stock_amount($product);
    }

    /**
     * Check if the product is a variable product
     *
     * @return bool
     */
    private function isVariable(): bool
    {
        $product = $this->getProduct();
        return $product && $product->is_type('variable');
    }

    /**
     * Get variation attributes for a variable product
     *
     * @return array
     */
    private function getVariationAttributes(): array
    {
        $product = $this->getProduct();

        if (!$product || !$product->is_type('variable')) {
            return [];
        }

        /** @var \WC_Product_Variable $product */
        $attributes = [];
        $variation_attributes = $product->get_variation_attributes();

        foreach ($variation_attributes as $attribute_name => $options) {
            // WooCommerce expects attribute names in the format "attribute_name" or "attribute_pa_name"
            // We need to ensure consistency with what get_available_variations() returns
            $attribute_key = 'attribute_' . sanitize_title($attribute_name);

            $attributes[$attribute_key] = [
                'name' => wc_attribute_label($attribute_name),
                'slug' => sanitize_title($attribute_name),
                'key' => $attribute_key,
                'options' => $options,
            ];
        }

        return $attributes;
    }

    /**
     * Get available variations with their data
     *
     * @return array
     */
    private function getAvailableVariations(): array
    {
        $product = $this->getProduct();

        if (!$product || !$product->is_type('variable')) {
            return [];
        }

        /** @var \WC_Product_Variable $product */
        $available_variations = [];
        $variations = $product->get_available_variations();

        foreach ($variations as $variation) {
            $variation_obj = wc_get_product($variation['variation_id']);

            if (!$variation_obj) {
                continue;
            }

            // Get stock status
            $stock_quantity = $variation_obj->get_stock_quantity();
            $stock_status = $variation_obj->get_stock_status();

            // Properly convert to boolean - stock 0 or negative means out of stock
            $is_in_stock = $stock_status === 'instock' &&
                          $variation_obj->is_in_stock() &&
                          ($stock_quantity === null || $stock_quantity > 0);

            $available_variations[] = [
                'variation_id' => $variation['variation_id'],
                'attributes' => $variation['attributes'],
                'price_html' => $variation_obj->get_price_html(),
                'display_price' => $variation_obj->get_price(),
                'display_regular_price' => $variation_obj->get_regular_price(),
                'is_in_stock' => (bool)$is_in_stock, // Force boolean conversion
                'is_purchasable' => (bool)$variation_obj->is_purchasable(),
                'is_on_sale' => (bool)$variation_obj->is_on_sale(),
                'stock_quantity' => (int)$stock_quantity,
                'stock_status' => $stock_status,
                'max_qty' => $variation_obj->get_max_purchase_quantity(),
                'min_qty' => $variation_obj->get_min_purchase_quantity(),
                'image_id' => $variation_obj->get_image_id(),
            ];
        }

        return $available_variations;
    }
}
