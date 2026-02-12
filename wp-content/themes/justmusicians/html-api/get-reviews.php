<?php
$MAX_REVIEWS_TO_DISPLAY = 10; // Remove after pagination is implemented for reviews on listing pages and venue pages
$reviewee_id = get_query_var('reviewee-id');
$reviews = get_reviews(get_query_var('review-post-type'), $reviewee_id);
$review_count = count($reviews);
$average_rating = $review_count > 0 ? array_sum(array_column($reviews, 'rating')) / $review_count : 0;


// Return reviews or no review state
if (count($reviews) > 0) {
    foreach($reviews as $index => $review) {
        if ($index < $MAX_REVIEWS_TO_DISPLAY) {
            echo get_template_part('template-parts/reviews/basic-review', '', [
                'rating'              => $review['rating'],
                'review'              => $review['review'],
                'author_name'         => $review['author_name'],
                'author_organization' => $review['author_organization'],
                'author_position'     => $review['author_position'],
                'author_image_url'    => $review['author_image_url'],
            ]);
        }
    }
    echo get_template_part('template-parts/reviews/write-review-button', '', []);
} else {
    echo get_template_part('template-parts/reviews/no-reviews', '', [] );
}

?>


<!-- Return review count -->
<span id="review-count" hx-swap-oob="outerHTML"><?php echo $review_count; ?></span>

<!-- Return average rating stars in heading -->
<div id="hero-average-rating" class="flex gap-x-1 text-yellow w-32 mb-4" hx-swap-oob="outerHTML">
    <?php echo get_template_part('template-parts/reviews/rating-stars-display', '', [ 'rating' => $average_rating, ]); ?>
</div>

<!-- Return average rating stars -->
<div id="average-rating" class="flex gap-x-1 text-yellow w-24" hx-swap-oob="outerHTML">
    <?php echo get_template_part('template-parts/reviews/rating-stars-display', '', [ 'rating' => $average_rating, ]); ?>
</div>

<!-- Return average rating stars with count -->
<div id="rating-with-count" hx-swap-oob="outerHTML">
    <?php echo get_template_part('template-parts/reviews/rating-stars-with-count', '', [
        'rating'       => $average_rating,
        'review_count' => $review_count,
    ]); ?>
</div>
