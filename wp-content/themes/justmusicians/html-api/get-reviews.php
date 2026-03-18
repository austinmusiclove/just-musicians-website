<?php
$reviewee_id = get_query_var('reviewee-id');
$page = isset($_GET['page']) ? $_GET['page'] : null;

$gr_result    = get_reviews(get_query_var('review-post-type'), $reviewee_id, $page);
$reviews      = $gr_result['reviews'];
$review_count = $gr_result['review_count'];
$rating       = $gr_result['rating'];


// Return reviews section
echo get_template_part('template-parts/reviews/basic-reviews-section', '', [
    'reviews'      => $reviews,
    'review_count' => $review_count,
]);

?>


<!-- Return review count -->
<span id="review-count" hx-swap-oob="outerHTML"><?php echo $review_count; ?></span>

<!-- Return average rating stars in heading -->
<div id="hero-average-rating" class="flex gap-x-1 text-yellow w-32 mb-4" hx-swap-oob="outerHTML">
    <?php echo get_template_part('template-parts/reviews/rating-stars-display', '', [ 'rating' => $rating, ]); ?>
</div>

<!-- Return average rating stars -->
<div id="average-rating" class="flex gap-x-1 text-yellow w-24" hx-swap-oob="outerHTML">
    <?php echo get_template_part('template-parts/reviews/rating-stars-display', '', [ 'rating' => $rating, ]); ?>
</div>

<!-- Return average rating stars with count -->
<div id="rating-with-count" hx-swap-oob="outerHTML">
    <?php echo get_template_part('template-parts/reviews/rating-stars-with-count', '', [
        'rating'       => $rating,
        'review_count' => $review_count,
    ]); ?>
</div>
