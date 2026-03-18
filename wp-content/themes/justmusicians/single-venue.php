<?php
/**
 * The template for displaying venues
 *
 * @package JustMusicians
 */

$gr_result    = get_reviews('venue_review', get_the_ID());
$reviews      = $gr_result['reviews'];
$review_count = $gr_result['review_count'];
$rating       = $gr_result['rating'];

get_header();

echo get_template_part('template-parts/venues/hero', '', [ 'rating' => $rating ]);
echo get_template_part('template-parts/venues/content', '', [
    'reviews'      => $reviews,
    'review_count' => $review_count,
    'rating'       => $rating,
]);

get_footer();
