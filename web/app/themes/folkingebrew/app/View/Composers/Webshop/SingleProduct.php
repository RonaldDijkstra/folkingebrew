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
        $product = wc_get_product(get_the_ID());

        return [
            'product' => $product,
            'breadcrumbs' => $this->getBreadcrumbs($product),
            'discountPercentage' => $this->getDiscountPercentage($product),
            'attributes' => $this->getAttributes($product),
        ];
    }

    // public function getProduct()
    // {
    //     $product = new \WC_Product(get_the_ID());
    //     return $product;
    // }

    private function getDiscountPercentage($product): ?int
    {
        if (!$product) {
            return null;
        }

        $regularPrice = (float) $product->get_regular_price();
        $salePrice = (float) $product->get_sale_price();

        // No discount if no sale price or if regular price is 0
        if (!$salePrice || $regularPrice <= 0) {
            return null;
        }

        // Calculate discount percentage
        $discount = (($regularPrice - $salePrice) / $regularPrice) * 100;

        return (int) round($discount);
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
            'text' => get_the_title(),
            'url'  => '',
        ];

        return $breadcrumbs;
    }

    private function getAttributes($product): array
    {
        if (!$product) {
            return [];
        }

        $attributes = [];
        $productAttributes = $product->get_attributes();

        foreach ($productAttributes as $attribute) {
            if (!$attribute->get_visible()) {
                continue;
            }

            $name = $attribute->get_name();
            $options = $attribute->get_options();

            // Convert option IDs to names if it's a taxonomy attribute
            if ($attribute->is_taxonomy()) {
                $terms = [];
                foreach ($options as $termId) {
                    $term = get_term($termId);
                    if ($term && !is_wp_error($term)) {
                        $terms[] = $term->name;
                    }
                }
                $value = implode(', ', $terms);
            } else {
                // For non-taxonomy attributes, options is already an array of values
                $value = implode(', ', $options);
            }

            if ($value) {
                $attributes[] = [
                    'name' => wc_attribute_label($name),
                    'value' => $value,
                ];
            }
        }

        return $attributes;
    }
}