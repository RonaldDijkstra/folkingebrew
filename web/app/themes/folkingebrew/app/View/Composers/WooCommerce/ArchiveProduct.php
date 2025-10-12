<?php

namespace App\View\Composers\WooCommerce;

use Roots\Acorn\View\Composer;

class ArchiveProduct extends Composer
{
    protected static $views = [
        'woocommerce/archive-product',
    ];

    public function with()
    {
        return [
            'products' => $this->getProducts(),
            'notFoundText' => __('No products found', 'folkingebrew'),
            'title' => $this->getTitle(),
        ];
    }

    private function getProducts()
    {
        $args = [
            'post_type' => 'product',
            'posts_per_page' => -1,
        ];

        // Check if we're viewing a product category archive
        if (is_product_category()) {
            $current_category = get_queried_object();
            if ($current_category) {
                $args['tax_query'] = [
                    [
                        'taxonomy' => 'product_cat',
                        'field' => 'term_id',
                        'terms' => $current_category->term_id,
                    ],
                ];
            }
        }

        // Check if we're viewing a product tag archive
        if (is_product_tag()) {
            $current_tag = get_queried_object();
            if ($current_tag) {
                $args['tax_query'] = [
                    [
                        'taxonomy' => 'product_tag',
                        'field' => 'term_id',
                        'terms' => $current_tag->term_id,
                    ],
                ];
            }
        }

        return get_posts($args);
    }

    private function getTitle()
    {
        if (is_product_category()) {
            return __('Products', 'folkingebrew');
        }

        if (is_product_tag()) {
            return __('Products', 'folkingebrew');
        }

        return __('All Products', 'folkingebrew');
    }
}
