<?php

namespace Custom\Setup\CustomPostTypes;

use Custom\Setup\CustomPostTypes\AbstractCustomPostType;

class Beers extends AbstractCustomPostType
{
    protected string $id = 'beers';
    protected string $label = 'Beer';
    protected string $plural_label = 'Beers';
    protected string $name = 'beers';
    protected string $instructions = 'Enable the custom post type <strong>Beers</strong>.';
    protected string $menu_icon = 'dashicons-beer';
    protected bool $public = true;
    protected bool $show_ui = true;
    protected bool $show_in_nav_menus = false;
    protected bool $publicly_queryable = true;
    protected bool $hierarchical = false;
    protected string $query_var = 'beers';
    protected array|bool $rewrite = false;
    protected string|bool $has_archive = true;
    protected bool $show_in_rest = true;
    protected string $capability_type = 'post';
    protected array $supports = ['title', 'page-attributes'];

    public function register(): void
    {
        parent::register();
        $this->addAdminColumns();
    }

    /**
     * Add custom admin columns for beers
     */
    private function addAdminColumns(): void
    {
        add_filter('manage_beers_posts_columns', [$this, 'addCustomColumns']);
        add_action('manage_beers_posts_custom_column', [$this, 'displayCustomColumns'], 10, 2);
        add_filter('manage_edit-beers_sortable_columns', [$this, 'makeSortableColumns']);
        add_action('admin_head', [$this, 'addColumnStyles']);
    }

    /**
     * Add custom columns to the admin table
     */
    public function addCustomColumns(array $columns): array
    {
        // Add image as first column, then other columns
        $new_columns = [];
        $new_columns['beer_image'] = 'Image';

        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ($key === 'title') {
                $new_columns['beer_id'] = 'Beer ID';
            }
        }
        return $new_columns;
    }

    /**
     * Display content for custom columns
     */
    public function displayCustomColumns(string $column, int $post_id): void
    {
        switch ($column) {
            case 'beer_image':
                $this->displayImageColumn($post_id);
                break;
            case 'beer_id':
                $this->displayBeerIdColumn($post_id);
                break;
        }
    }

    /**
     * Display the beer image in the admin column
     */
    private function displayImageColumn(int $post_id): void
    {
        $image = get_field('image', $post_id);

        if ($image && is_array($image) && isset($image['sizes']['thumbnail'])) {
            $thumbnail_url = $image['sizes']['thumbnail'];
            $alt_text = $image['alt'] ?? 'Beer image';
            echo '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr($alt_text) . '" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">';
        } else {
            echo '<span style="color: #999; font-size: 11px;">No image</span>';
        }
    }

    /**
     * Display the beer ID in the admin column
     */
    private function displayBeerIdColumn(int $post_id): void
    {
        $beer_id = get_field('beer_id', $post_id);

        if ($beer_id) {
            echo '<strong>' . esc_html($beer_id) . '</strong>';
        } else {
            echo '<span style="color: #999;">No ID</span>';
        }
    }

    /**
     * Make beer_id column sortable
     */
    public function makeSortableColumns(array $columns): array
    {
        $columns['beer_id'] = 'beer_id';
        return $columns;
    }

    /**
     * Add custom CSS for column widths
     */
    public function addColumnStyles(): void
    {
        global $current_screen;

        if ($current_screen && $current_screen->post_type === 'beers') {
            echo '<style>
                .wp-list-table .column-beer_image {
                    width: 60px;
                    text-align: center;
                }
                .wp-list-table .column-beer_id {
                    width: 80px;
                }
            </style>';
        }
    }
}
