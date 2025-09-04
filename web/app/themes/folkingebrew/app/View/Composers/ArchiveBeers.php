<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class ArchiveBeers extends Composer
{
    protected static $views = [
        'archive-beers',
    ];

    public function with()
    {
        $query = $this->getBeersQuery();
        
        return [
            'beers' => $this->enhanceBeersWithAcfFields($query->posts),
            'pagination' => [
                'current_page' => max(1, get_query_var('paged')),
                'total_pages' => $query->max_num_pages,
                'total_posts' => $query->found_posts,
                'posts_per_page' => $query->query_vars['posts_per_page'],
            ],
            'title' => get_field('archive_title', 'option'),
        ];
    }

    private function getBeersQuery()
    {
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        $posts_per_page = get_field('posts_per_page', 'option') ?: 10;

        $args = [
            'post_type' => 'beers',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'meta_key' => 'id',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'meta_query' => [
                [
                    'key' => 'id',
                    'compare' => 'EXISTS'
                ]
            ]
        ];

        return new \WP_Query($args);
    }

    private function enhanceBeersWithAcfFields($posts)
    {
        return array_map(function ($post) {
            // Add ACF fields to the post object          
          
            $post->image = get_field('image', $post->ID);
            
            $post->style = get_field('style', $post->ID);
            $post->id = get_field('id', $post->ID);
            $post->abv = get_field('abv', $post->ID);
            $post->url = get_permalink($post->ID);
            
            return $post;
        }, $posts);
    }
}