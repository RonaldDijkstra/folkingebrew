<?php

namespace App\View\Composers\Webshop;

use Roots\Acorn\View\Composer;
use WP_Post;
use WP_Term;

class ArchiveProduct extends Composer
{
    /** @var string[] */
    protected static $views = [
        'webshop/archive-product',
        'webshop/archive-product-category',
    ];

    /** @var array<string,int> */
    private const CATEGORY_ORDER = [
        'beer'        => 1,
        'packs'       => 2,
        'merchandise' => 3,
    ];

    /** @var WP_Term|null */
    private $queriedTerm = null;

    public function with(): array
    {
        return [
            'productsByCategory' => $this->getProductsByCategory(),
            'notFoundText'       => __('No products found', 'folkingebrew'),
            'title'              => $this->getTitle(),
            'isShopPage'         => $this->isShopLanding(),
            'breadcrumbs'        => $this->getBreadcrumbs(),
        ];
    }

    /**
     * Build the grouped product list based on current archive context.
     */
    private function getProductsByCategory(): array
    {
        // Category archive → only that category
        if (is_product_category()) {
            $category = $this->getQueriedTerm();
            if ($category) {
                $products = $this->queryProducts([
                    'tax_query' => [[
                        'taxonomy' => 'product_cat',
                        'field'    => 'term_id',
                        'terms'    => $category->term_id,
                    ]],
                ]);

                // Transform products into prepared data arrays
                $preparedProducts = array_map([$this, 'prepareProductData'], $products);

                return [[
                    'category_name' => $category->name,
                    'category_slug' => $category->slug,
                    'products'      => $preparedProducts,
                ]];
            }
        }

        // Tag archive → fetch by tag, then group by categories
        if (is_product_tag()) {
            $tag = $this->getQueriedTerm();
            if ($tag) {
                $products = $this->queryProducts([
                    'tax_query' => [[
                        'taxonomy' => 'product_tag',
                        'field'    => 'term_id',
                        'terms'    => $tag->term_id,
                    ]],
                ]);

                return $this->groupProductsByCategory($products);
            }
        }

        // Shop landing → all products, grouped by categories
        $products = $this->queryProducts();
        return $this->groupProductsByCategory($products);
    }

    /**
     * Query products with sensible defaults.
     *
     * @param array<string,mixed> $extraArgs
     * @return WP_Post[]
     */
    private function queryProducts(array $extraArgs = []): array
    {
        $args = array_replace_recursive([
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
            // Keep filters enabled so Woo can adjust queries if needed
            'suppress_filters' => false,
            // Exclude out of stock products
            'meta_query'     => [[
                'key'     => '_stock_status',
                'value'   => 'outofstock',
                'compare' => '!=',
            ]],
        ], $extraArgs);

        /** @var WP_Post[] $posts */
        $posts = get_posts($args);
        return $posts;
    }

    /**
     * Group a flat list of products into category buckets.
     *
     * @param WP_Post[] $products
     * @return array<int,array{category_name:string,category_slug:string,products:array[]}>
     */
    private function groupProductsByCategory(array $products): array
    {
        $grouped = [];

        foreach ($products as $product) {
            $terms = get_the_terms($product->ID, 'product_cat');
            $productData = $this->prepareProductData($product);

            if ($terms && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    // Skip "uncategorized"
                    if (strtolower($term->slug) === 'uncategorized') {
                        continue;
                    }

                    if (!isset($grouped[$term->term_id])) {
                        $grouped[$term->term_id] = [
                            'category_name' => $term->name,
                            'category_slug' => $term->slug,
                            'products'      => [],
                        ];
                    }

                    $grouped[$term->term_id]['products'][] = $productData;
                }
            } else {
                // Bucket for products without a category
                $key = 0;
                if (!isset($grouped[$key])) {
                    $grouped[$key] = [
                        'category_name' => __('Other Products', 'folkingebrew'),
                        'category_slug' => 'other',
                        'products'      => [],
                    ];
                }
                $grouped[$key]['products'][] = $productData;
            }
        }

        // Normalize to indexed array
        $grouped = array_values($grouped);

        // Allow projects to alter the preferred order
        $orderMap = apply_filters('folkingebrew/shop_category_order', self::CATEGORY_ORDER);

        // Sort buckets using preferred order, unknown slugs sink to the bottom
        usort($grouped, static function ($a, $b) use ($orderMap) {
            $aSlug  = strtolower($a['category_slug']);
            $bSlug  = strtolower($b['category_slug']);
            $aOrder = $orderMap[$aSlug] ?? 999;
            $bOrder = $orderMap[$bSlug] ?? 999;

            if ($aOrder === $bOrder) {
                // Secondary sort by name for stability
                return strcasecmp($a['category_name'], $b['category_name']);
            }

            return $aOrder <=> $bOrder;
        });

        return $grouped;
    }

    /**
     * Prepare product data including price, image, and attributes.
     *
     * @param WP_Post $post
     * @return array<string,mixed>
     */
    private function prepareProductData(WP_Post $post): array
    {
        $wcProduct = wc_get_product($post->ID);

        $data = [
            'id'               => $post->ID,
            'title'            => $post->post_title,
            'permalink'        => get_permalink($post->ID),
            'price'            => $wcProduct ? $wcProduct->get_price_html() : '',
            'thumbnail'        => null,
            'abv'              => null,
            'style'            => null,
            'add_to_cart_url'  => '',
            'is_purchasable'   => false,
            'is_in_stock'      => false,
            'product_type'     => '',
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
        }

        return $data;
    }

    /**
     * Compute the archive title.
     */
    private function getTitle(): string
    {
        if (is_product_category() || is_product_tag()) {
            $term = $this->getQueriedTerm();

            if (is_product_category()) {
                return $term ? $term->name : __('Products', 'folkingebrew');
            }

            if (is_product_tag()) {
                $prefix = __('Tag: ', 'folkingebrew');
                return $term ? $prefix . $term->name : __('Products', 'folkingebrew');
            }
        }

        return __('Webshop', 'folkingebrew');
    }

    /**
     * Determine if we're on the shop landing (not cat or tag).
     */
    private function isShopLanding(): bool
    {
        return is_shop() && !is_product_category() && !is_product_tag();
    }

    /**
     * Memoized queried term for category/tag archives.
     */
    private function getQueriedTerm(): ?WP_Term
    {
        if ($this->queriedTerm !== null) {
            return $this->queriedTerm;
        }

        $obj = get_queried_object();
        $this->queriedTerm = ($obj instanceof WP_Term) ? $obj : null;

        return $this->queriedTerm;
    }

    /**
     * Build breadcrumb navigation items.
     *
     * @return array<int,array{text:string,url:string}>
     */
    private function getBreadcrumbs(): array
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

        // Add category or tag as the final item
        if (is_product_category() || is_product_tag()) {
            $term = $this->getQueriedTerm();
            if ($term) {
                $breadcrumbs[] = [
                    'text' => $term->name,
                    'url'  => get_term_link($term),
                ];
            }
        }

        return $breadcrumbs;
    }
}
