<?php

namespace Custom\Setup\Blocks;

use Custom\Setup\ServiceInterface;
use function Roots\view;
use Illuminate\Support\Facades\Vite;

class RegisterBlocks implements ServiceInterface
{
    public function register(): void
    {
        add_action('acf/init', [$this, 'registerBlockTypes'], 20);
        add_filter('allowed_block_types_all', [$this, 'allowedBlockTypes'], 10, 2);
        add_filter('block_categories_all', [$this, 'blockCategories'], 10, 2);

    }

    public function registerBlockTypes(): void
    {
        if (function_exists('acf_register_block_type')) {
            $blocks = $this->getBlockDefinitions();

            foreach ($blocks as $block) {
                if (!acf_get_block_type($block['name'])) {
                    acf_register_block_type($block);
                }
            }
        }
    }

    public function getBlockDefinitions(): array
    {
        $blocks = [
            [
                'name' => 'hero',
                'title' => 'Hero',
                'category' => 'content',
                'icon' => '<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24"><!-- Generator: Adobe Illustrator 29.7.1, SVG Export Plug-In . SVG Version: 2.1.1 Build 8)  --><rect width="24" height="24" fill="none"/><path d="M12,4.9l1.6,5.1.2.5h5.9l-4.3,3.1-.4.3.2.5,1.6,5.1-4.3-3.1-.4-.3-.4.3-4.3,3.1,1.6-5.1.2-.5-.4-.3-4.3-3.1h5.9l.2-.5,1.6-5.1M12,2.5l-2.4,7.3H2l6.2,4.5-2.4,7.3,6.2-4.5,6.2,4.5-2.4-7.3,6.2-4.5h-7.6l-2.4-7.3h0Z"/></svg>',
                'description' => 'A hero block',
                'apiVersion' => 3,
                'render_callback' => function ($block, $content = '', $is_preview = false) {
                    // Check if this is the block inserter example (no data yet)
                    $is_example = empty($block['data']) || (empty(get_fields()) && $is_preview);

                    if ($is_example) {
                        $this->getBlockPreviewImage($block['name']);
                    } else {
                        echo view('blocks.hero');
                    }
                },
                'mode' => 'preview',
                'supports' => [
                    'mode' => false,
                    'align' => ['wide', 'full'],
                    'anchor' => true,
                    'jsx' => true,
                ],
                'example' => [
                    'attributes' => [
                        'mode' => 'preview',
                        'data' => [],
                    ]
                ],
            ],
            [
                'name' => 'content',
                'title' => 'Content',
                'category' => 'content',
                'icon' => '<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24"><!-- Generator: Adobe Illustrator 29.7.1, SVG Export Plug-In . SVG Version: 2.1.1 Build 8)  --><rect width="24" height="24" fill="none"/><path d="M12,4.9l1.6,5.1.2.5h5.9l-4.3,3.1-.4.3.2.5,1.6,5.1-4.3-3.1-.4-.3-.4.3-4.3,3.1,1.6-5.1.2-.5-.4-.3-4.3-3.1h5.9l.2-.5,1.6-5.1M12,2.5l-2.4,7.3H2l6.2,4.5-2.4,7.3,6.2-4.5,6.2,4.5-2.4-7.3,6.2-4.5h-7.6l-2.4-7.3h0Z"/></svg>',
                'description' => 'A content block with text and image.',
                'apiVersion' => 3,
                'render_callback' => function ($block, $content = '', $is_preview = false) {
                    // Check if this is the block inserter example (no data yet)
                    $is_example = empty($block['data']) || (empty(get_fields()) && $is_preview);

                    if ($is_example) {
                        $this->getBlockPreviewImage($block['name']);
                    } else {
                        echo view('blocks.content');
                    }
                },
                'mode' => 'preview',
                'supports' => [
                    'mode' => false,
                    'align' => ['wide', 'full'],
                    'anchor' => true,
                    'jsx' => true,
                ],
                'example' => [
                    'attributes' => [
                        'mode' => 'preview',
                        'data' => [],
                    ]
                ],
            ],
            [
                'name' => 'review-slider',
                'title' => 'Review Slider',
                'category' => 'content',
                'icon' => '<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24"><!-- Generator: Adobe Illustrator 29.7.1, SVG Export Plug-In . SVG Version: 2.1.1 Build 8)  --><rect width="24" height="24" fill="none"/><path d="M12,4.9l1.6,5.1.2.5h5.9l-4.3,3.1-.4.3.2.5,1.6,5.1-4.3-3.1-.4-.3-.4.3-4.3,3.1,1.6-5.1.2-.5-.4-.3-4.3-3.1h5.9l.2-.5,1.6-5.1M12,2.5l-2.4,7.3H2l6.2,4.5-2.4,7.3,6.2-4.5,6.2,4.5-2.4-7.3,6.2-4.5h-7.6l-2.4-7.3h0Z"/></svg>',
                'description' => 'A review slider block.',
                'render_callback' => function () {
                    echo view('blocks.review-slider');
                },
                'mode' => 'edit',
                'supports' => [
                    'mode' => false,
                    'align' => ['wide', 'full'],
                ],
            ],
            [
                'name' => 'agenda',
                'title' => 'Agenda',
                'category' => 'content',
                'icon' => '<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24"><!-- Generator: Adobe Illustrator 29.7.1, SVG Export Plug-In . SVG Version: 2.1.1 Build 8)  --><rect width="24" height="24" fill="none"/><path d="M12,4.9l1.6,5.1.2.5h5.9l-4.3,3.1-.4.3.2.5,1.6,5.1-4.3-3.1-.4-.3-.4.3-4.3,3.1,1.6-5.1.2-.5-.4-.3-4.3-3.1h5.9l.2-.5,1.6-5.1M12,2.5l-2.4,7.3H2l6.2,4.5-2.4,7.3,6.2-4.5,6.2,4.5-2.4-7.3,6.2-4.5h-7.6l-2.4-7.3h0Z"/></svg>',
                'description' => 'An agenda block.',
                'apiVersion' => 3,
                'render_callback' => function ($block, $content = '', $is_preview = false, $post_id = 0, $wp_block = null, $context = []) {
                    echo view('blocks.agenda', [
                        'block' => $block,
                        'is_preview' => $is_preview,
                        'post_id' => $post_id,
                        'context' => $context,
                    ]);
                },
                'mode' => 'preview',
                'supports' => [
                    'mode' => false,
                    'align' => ['wide', 'full'],
                    'anchor' => true,
                    'jsx' => true,
                ],
                'example' => [
                    'attributes' => [
                        'mode' => 'preview',
                        'data' => [],
                    ]
                ],
            ],
        ];

        usort($blocks, function ($a, $b) {
            return strcmp($a['title'], $b['title']);
        });

        return $blocks;
    }

    public function allowedBlockTypes($allowed, \WP_Block_Editor_Context $context): array
    {
        if (empty($context->post)) {
            return [];
        }

        // Get current template slug, normalize to the basename
        $template = get_page_template_slug($context->post) ?: 'default';
        $template = $template === 'default' ? 'default' : basename($template);

        // Map templates to allowed blocks
        $map = [
            // Blade templates
            'template-custom.blade.php' => ['acf/content'],

            // Fallback
            'default'                    => ['acf/agenda', 'acf/content', 'acf/hero', 'acf/review-slider', 'woocommerce/cart'],
        ];

        $base = $map[$template] ?? $map['default'];

        if ($allowed === true) {
            return $base;
        }

        if (is_array($allowed) && $allowed) {
            return array_values(array_intersect($allowed, $base));
        }

        return $base;
    }

    /**
     * Define the block categories.
     *
     * @param array $blockCategories
     * @param WP_Block_Editor_Context $blockEditorContext
     * @return array
     */
    public function blockCategories(array $blockCategories, \WP_Block_Editor_Context $blockEditorContext): array
    {
        if (empty($blockEditorContext->post)) {
            return $blockCategories;
        }

        return [
            [
                'slug'  => 'content',
                'title' => 'Content Blocks',
                'icon'  => null,
            ],
            [
                'slug'  => 'woocommerce',
                'title' => 'Webshop',
                'icon'  => null,
            ],
        ];
    }

    private function getBlockPreviewImage($blockName)
    {
        // Remove 'acf/' prefix if present in $blockName
        if (strpos($blockName, 'acf/') === 0) {
            $blockName = substr($blockName, 4);
        }

        $imageUrl = Vite::asset('resources/images/blocks/' . $blockName . '.png');

        echo '<div style="background: #f0f0f0; border-radius: 4px;overflow:hidden;border:1px solid #ccc;">';
        echo '<img src="' . esc_url($imageUrl) . '" alt="' . esc_attr($blockName) . ' Preview" style="max-width: 100%; height: auto;" />';
        echo '</div>';
    }
}
