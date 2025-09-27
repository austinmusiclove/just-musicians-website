<?php


$listing_id = get_query_var('listing-id');
$reviews = get_listing_reviews($listing_id);
$review_count = count($reviews);
$average_rating = $review_count > 0 ? array_sum(array_column($reviews, 'rating')) / $review_count : 0;


// Return reviews or no review state
if (count($reviews) > 0) {
    foreach($reviews as $review) {
        echo get_template_part('template-parts/reviews/listing-review', '', [
            'rating'              => $review['rating'],
            'review'              => $review['review'],
            'author_name'         => $review['author_name'],
            'author_organization' => $review['author_organization'],
            'author_position'     => $review['author_position'],
            'author_image_url'    => $review['author_image_url'],
        ]);
    }
} else {
    echo get_template_part('template-parts/reviews/no-listing-reviews', '', [] );
}

?>


<!-- Return review count -->
<span id="review-count" hx-swap-oob="outerHTML"><?php echo $review_count; ?></span>


<!-- Return average rating stars -->
<div id="average-rating" class="flex gap-x-1 text-yellow w-24" hx-swap-oob="outerHTML">
    <?php echo get_template_part('template-parts/reviews/rating-stars', '', [ 'rating' => $average_rating, ]); ?>
</div>
