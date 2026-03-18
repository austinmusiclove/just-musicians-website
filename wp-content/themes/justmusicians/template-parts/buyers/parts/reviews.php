<div class="w-full"
    x-init="
        reviewPostType = 'buyer_review';
        revieweeId     = '<?php echo $args['buyer_id']; ?>';
        revieweeName   = '<?php echo $args['display_name']; ?>';
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
        hx-get="<?php echo site_url('/wp-html/v1/reviews/buyer_review/' . $args['buyer_id']); ?>"
        hx-trigger="fetch-reviews from:window"
    >
        <?php echo get_template_part('template-parts/reviews/basic-reviews-section', '', [
            'reviews'      => $args['reviews'],
            'review_count' => $args['review_count'],
        ]); ?>
    </div>


</div>
