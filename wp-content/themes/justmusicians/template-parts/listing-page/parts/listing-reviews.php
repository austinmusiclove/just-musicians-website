<div class="w-full"
    x-init="
        reviewPostType = 'listing_review';
        revieweeId     = '<?php echo get_the_ID(); ?>';
        revieweeName   = '<?php echo get_field('name'); ?>';
    "
>

    <!-- Heading -->
    <h2 class="text-22 font-bold mb-5 flex gap-2">Reviews
        <span class="font-normal text-16">(<span id="review-count" hx-swap-oob="outerHTML"><?php echo $args['review_count']; ?></span>)</span>
        <div id="average-rating" class="flex gap-x-1 text-yellow w-24" hx-swap-oob="outerHTML">
            <?php echo get_template_part('template-parts/reviews/rating-stars-display', '', [ 'rating' => $args['rating'], ]); ?>
        </div>
    </h2>

    <!-- Reviews -->
    <div
        hx-get="<?php echo site_url('/wp-html/v1/reviews/listing_review/' . $args['post_id']); ?>"
        hx-trigger="fetch-reviews from:window"
    >
        <?php $MAX_REVIEWS_TO_DISPLAY = 10; ?>
        <?php if ($args['review_count'] > 0) {
            foreach($args['reviews'] as $index => $review) {
                if ($index < $MAX_REVIEWS_TO_DISPLAY) {
                    echo get_template_part('template-parts/reviews/basic-review', '', [
                        'rating'              => $review['rating'],
                        'review'              => $review['review'],
                        'date'                => $review['date'],
                        'author_name'         => $review['author_name'],
                        'author_organization' => $review['author_organization'],
                        'author_position'     => $review['author_position'],
                        'author_image_url'    => $review['author_image_url'],
                        'author_id'           => $review['author_id'],
                    ]);
                }
            }
            echo get_template_part('template-parts/reviews/write-review-button', '', []);
        } else {
            echo get_template_part('template-parts/reviews/no-reviews', '', [] );
        } ?>

    </div>


</div>
