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
            'text' => get_the_title(),
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
}
