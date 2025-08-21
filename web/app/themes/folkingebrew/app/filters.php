<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * Add font-sans class to body.
 *
 * @param array $classes
 * @return array
 */
add_filter('body_class', function ($classes) {
    $classes[] = 'font-sans';
    return $classes;
});
