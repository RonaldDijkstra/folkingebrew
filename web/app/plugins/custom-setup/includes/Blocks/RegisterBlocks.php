<?php 

namespace Custom\Setup\Blocks;

use Custom\Setup\ServiceInterface;
use function Roots\view;

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
                'description' => 'A hero block with customizable title.',
                'render_callback' => function () {
                    echo view('blocks.hero');
                },
                'mode' => 'edit',
                'supports' => [
                    'mode' => false,
                    'align' => ['wide', 'full'],
                ],
            ],
            [
                'name' => 'content',
                'title' => 'Content',
                'category' => 'content',
                'icon' => '<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24"><!-- Generator: Adobe Illustrator 29.7.1, SVG Export Plug-In . SVG Version: 2.1.1 Build 8)  --><rect width="24" height="24" fill="none"/><path d="M12,4.9l1.6,5.1.2.5h5.9l-4.3,3.1-.4.3.2.5,1.6,5.1-4.3-3.1-.4-.3-.4.3-4.3,3.1,1.6-5.1.2-.5-.4-.3-4.3-3.1h5.9l.2-.5,1.6-5.1M12,2.5l-2.4,7.3H2l6.2,4.5-2.4,7.3,6.2-4.5,6.2,4.5-2.4-7.3,6.2-4.5h-7.6l-2.4-7.3h0Z"/></svg>',
                'description' => 'A content block with text and image.',
                'render_callback' => function () {
                    echo view('blocks.content');
                },
                'mode' => 'edit',
                'supports' => [
                    'mode' => false,
                    'align' => ['wide', 'full'],
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
        ];

        usort($blocks, function ($a, $b) {
            return strcmp($a['title'], $b['title']);
        });

        return $blocks;
    }

    public function allowedBlockTypes($allowedBlocks, \WP_Block_Editor_Context $blockEditorContext): array
    {
        $allowedBlocks = [];

        if (!isset($blockEditorContext->post)) {
            return $allowedBlocks;
        }

        $blocks = [
            'acf/content',
            'acf/hero',
            'acf/review-slider',
        ];

        $allowedBlocks = array_merge(
            $allowedBlocks,
            $blocks
        );

        return $allowedBlocks;
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
        ];
    }
}
