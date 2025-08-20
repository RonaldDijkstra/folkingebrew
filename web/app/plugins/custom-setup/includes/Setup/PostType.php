<?php 

namespace Custom\Setup\Setup;

use Custom\Setup\ServiceInterface;

class PostType implements ServiceInterface
{
    public function register()
    {
        add_action('init', [$this, 'enableExcerptForPages']);
        add_action('admin_init', [$this, 'removeCommentsAndTrackbacksSupport']);
        add_action('admin_init', [$this, 'addFeaturedImageColumn']);
        add_action('admin_head', [$this, 'featuredImageColumnStyling']);
        add_action('admin_footer', [$this, 'postStatusColor']);
    }

    /**
     * Enable excerpt for pages
     * If filled this gets used in the search index
     * and the excerpt shows on the result cards
     */
    public function enableExcerptForPages(): void
    {
        add_post_type_support('page', 'excerpt');
    }

    /**
     * Remove comments and trackbacks support from all post types
     *
     * @return void
     */
    public function removeCommentsAndTrackbacksSupport(): void
    {
        $post_types = get_post_types();

        foreach ($post_types as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }

    /**
     * Adds a column for the featured image to display in indexes
     *
     * @return void
     */
    public function addFeaturedImageColumn()
    {
        $post_types = get_post_types(array('public' => true), 'names');

        foreach ($post_types as $post_type) {
            if (post_type_supports($post_type, 'thumbnail')) {
                add_filter('manage_' . $post_type . '_posts_columns', [$this, 'addPostAdminThumbnailColumn'], 2);
                add_action('manage_' . $post_type . '_posts_custom_column', [$this, 'showPostThumbnailColumn'], 5, 2);
            }
        }
    }

    /**
     * Add the column for the featured image
     *
     * @param array $columns
     * @return array $columns
     */
    public function addPostAdminThumbnailColumn($columns): array
    {
        $customColumn['thumb'] = '';
        $columns = array_slice($columns, 0, 1, true) + $customColumn + array_slice($columns, 1, null, true);

        return $columns;
    }

    /**
     * Show the featured image but downsize it a bit
     *
     * @param array $columns
     * @return void
     */
    public function showPostThumbnailColumn($columns): void
    {
        switch($columns){
            case 'thumb':
                if(function_exists('the_post_thumbnail')) {
                    echo '<div style="width: 42px; height: 42px; overflow: hidden;">';
                    echo the_post_thumbnail('thumbnail', ['style' => 'width: 42px; height: 42px;']);
                    echo '</div>';
                }
                else {
                    echo 'Geen uitgelichte afbeelding beschikbaar';
                }
            break;
        }
    }

    /**
     * Style the featured image column
     *
     * @return void
     */
    public function featuredImageColumnStyling(): void
    {
        echo "
            <style>
                .widefat .column-thumb,
                .widefat tbody th.column-thumb { width: 46px; }
            </style>
            ";
    }

    /**
     * Additional styling for coloring the posts in posts indexes
     *
     * @return void
     */
    public function postStatusColor(): void
    {
        echo "
            <style>
                .status-draft {background: #FCF4E3 !important; }
                .status-pending {background: #DCEFF5 !important; }
                .status-publish {/* Keep the regular alternating colors */}
                .status-future {background: #C6EBF5 !important; }
                .status-private {background:#FFF1F1 !important; }
            </style>
            ";
    }
}
