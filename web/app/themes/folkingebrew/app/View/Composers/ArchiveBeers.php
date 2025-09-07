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
        $postsPerPage = get_field('posts_per_page', 'option') ?: 2;
        $paged = $this->getPaged();

        // Get all beers sorted by beer_id
        $allBeers = $this->getAllBeersSorted();

        // Calculate pagination
        $totalBeers = count($allBeers);
        $numberOfPages = (int) ceil($totalBeers / $postsPerPage);

        // Get beers for current page
        $offset = ($paged - 1) * $postsPerPage;
        $beersForPage = array_slice($allBeers, $offset, $postsPerPage);

        return [
            'beers' => $beersForPage,
            'numberOfPages' => $numberOfPages,
            'paged' => $paged,
            'postsPerPage' => $postsPerPage,
            'totalPosts' => $totalBeers,
            'title' => get_field('archive_title', 'option'),
        ];
    }

    private function getAllBeersSorted(): array
    {
        // Get ALL beers without pagination
        $allBeersQuery = new \WP_Query([
            'post_type' => 'beers',
            'posts_per_page' => -1, // Get all posts
            'post_status' => 'publish',
        ]);

        // Enhance with ACF fields
        $beers = $this->enhanceBeersWithAcfFields($allBeersQuery->posts);

        // Sort beers by beer_id field (higher numbers first)
        usort($beers, function ($a, $b) {
            $aId = (int) $a->beer_id;
            $bId = (int) $b->beer_id;
            return $bId <=> $aId; // Descending order (higher numbers first)
        });

        return $beers;
    }

    private function enhanceBeersWithAcfFields($posts)
    {
        return array_map(function ($post) {
            // Add ACF fields to the post object

            $post->image = get_field('image', $post->ID);
            $post->style = get_field('style', $post->ID);
            $post->beer_id = get_field('beer_id', $post->ID);
            $post->abv = get_field('abv', $post->ID);
            $post->url = get_permalink($post->ID);

            return $post;
        }, $posts);
    }


    private function getPaged(): int
    {
        return get_query_var('paged') ? (int) get_query_var('paged') : 1;
    }
}