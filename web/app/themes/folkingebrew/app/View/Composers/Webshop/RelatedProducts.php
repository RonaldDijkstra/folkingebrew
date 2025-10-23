<?php

namespace App\View\Composers\Webshop;

use Roots\Acorn\View\Composer;

class RelatedProducts extends Composer
{
    protected static $views = [
        'webshop.related-products',
    ];

    public function with()
    {
        return [
            'relatedProducts' => $this->getRelatedProducts(),
        ];
    }

    private function getRelatedProducts()
    {
        $relatedProducts = [];
        $maxProducts = 3;

        $product = wc_get_product(get_the_ID());

        if (!$product) {
            return [];
        }

        // First, get upsell products
        $upsellIds = $product->get_upsell_ids();

        if (!empty($upsellIds)) {
            foreach ($upsellIds as $upsellId) {
                if (count($relatedProducts) >= $maxProducts) {
                    break;
                }

                $upsellProduct = wc_get_product($upsellId);
                if ($upsellProduct && $upsellProduct->is_visible()) {
                    $post = get_post($upsellId);
                    if ($post) {
                        $relatedProducts[] = $this->prepareProductData($post);
                    }
                }
            }
        }

        // If we don't have enough products, fill with products from the same category
        if (count($relatedProducts) < $maxProducts) {
            $categoryProducts = $this->getProductsFromSameCategory(
                $product,
                $maxProducts - count($relatedProducts),
                array_merge([$product->get_id()], $upsellIds)
            );

            $relatedProducts = array_merge($relatedProducts, $categoryProducts);
        }

        return $relatedProducts;
    }

    private function getProductsFromSameCategory($product, $limit, $excludeIds = [])
    {
        $categoryIds = $product->get_category_ids();

        if (empty($categoryIds)) {
            return [];
        }

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => $limit,
            'post__not_in'   => $excludeIds,
            'tax_query'      => [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $categoryIds,
                ],
            ],
            'orderby'        => 'rand',
        ];

        $query = new \WP_Query($args);
        $products = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $categoryProduct = wc_get_product(get_the_ID());
                if ($categoryProduct && $categoryProduct->is_visible()) {
                    $products[] = $this->prepareProductData(get_post(get_the_ID()));
                }
            }
            wp_reset_postdata();
        }

        return $products;
    }

    /**
     * Prepare product data including price, image, and attributes.
     *
     * @param \WP_Post $post
     * @return array<string,mixed>
     */
    private function prepareProductData(\WP_Post $post): array
    {
        $wcProduct = wc_get_product($post->ID);

        $data = [
            'id'               => $post->ID,
            'title'            => $post->post_title,
            'permalink'        => get_permalink($post->ID),
            'price'            => $wcProduct ? $wcProduct->get_price_html() : '',
            'sale'             => false,
            'thumbnail'        => null,
            'abv'              => null,
            'style'            => null,
            'add_to_cart_url'  => '',
            'is_purchasable'   => false,
            'is_in_stock'      => false,
            'product_type'     => '',
            'new'              => false,
            'date_created'     => 0,
        ];

        // Get featured image
        if (has_post_thumbnail($post->ID)) {
            $data['thumbnail'] = get_the_post_thumbnail($post->ID, 'medium', ['class' => 'w-full h-auto']);
        }

        // Get attributes and cart data if WooCommerce product exists
        if ($wcProduct) {
            // Get ABV attribute
            $abv = $wcProduct->get_attribute('pa_abv');
            if (!$abv) {
                $abv = $wcProduct->get_attribute('abv');
            }
            $data['abv'] = $abv ?: null;

            // Get Style attribute
            $style = $wcProduct->get_attribute('pa_style');
            if (!$style) {
                $style = $wcProduct->get_attribute('style');
            }
            $data['style'] = $style ?: null;

            // Add to cart data
            $data['add_to_cart_url'] = $wcProduct->add_to_cart_url();
            $data['is_purchasable']  = $wcProduct->is_purchasable();
            $data['is_in_stock']     = $wcProduct->is_in_stock();
            $data['product_type']    = $wcProduct->get_type();
            $data['sale']            = $wcProduct->is_on_sale();
            $data['date_created']    = $wcProduct->get_date_created()->getTimestamp();
            $data['new']             = $wcProduct->get_date_created()->format('Y-m-d') > date('Y-m-d', strtotime('-3 days'));
        }

        return $data;
    }
}
