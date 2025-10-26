<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class SingleBeer extends Composer
{
    protected static $views = [
        'single-beers',
    ];

    public function with()
    {
        return [
            'title' => get_the_title(),
            'style' => get_field('style'),
            'abv' => get_field('abv'),
            'image' => get_field('image'),
            'wallpaper' => get_field('wallpaper'),
            'description' => get_field('description'),
            'specs' => get_field('specs'),
            'untappd_link' => get_field('untappd_link'),
            'archiveLink' => $this->getBeersArchiveLink(),
            'webshopProduct' => $this->getWebshopProduct(),
        ];
    }

    protected function getBeersArchiveLink()
    {
        return get_post_type_archive_link('beers');
    }

    protected function getWebshopProduct()
    {
        $product_post = get_field('webshop_product');

        if (!$product_post) {
            return null;
        }

        // Get WooCommerce product
        $product = wc_get_product($product_post->ID);

        if (!$product) {
            return null;
        }

        // Only return product data if it's in stock
        if (!$product->is_in_stock()) {
            return null;
        }

        // For variable products, show only the SINGLE variant price
        $priceHtml = $product->get_price_html();
        if ($product->is_type('variable')) {
            $singleVariationPrice = $this->getSingleVariantPrice($product);
            if ($singleVariationPrice) {
                $priceHtml = $singleVariationPrice;
            }
        }

        return [
            'id' => $product->get_id(),
            'title' => $product->get_name(),
            'url' => get_permalink($product->get_id()),
            'price' => $priceHtml,
            'stock_status' => $product->get_stock_status(),
        ];
    }

    /**
     * Get price HTML for the SINGLE variant of a variable product.
     *
     * @param \WC_Product_Variable $product
     * @return string|null
     */
    private function getSingleVariantPrice($product): ?string
    {
        $variations = $product->get_available_variations();

        foreach ($variations as $variation) {
            // Check if this variation has "SINGLE" in any of its attributes
            foreach ($variation['attributes'] as $attributeKey => $attributeValue) {
                if (stripos($attributeValue, 'SINGLE') !== false) {
                    $variationProduct = wc_get_product($variation['variation_id']);
                    if ($variationProduct) {
                        return $variationProduct->get_price_html();
                    }
                }
            }
        }

        return null;
    }
}
