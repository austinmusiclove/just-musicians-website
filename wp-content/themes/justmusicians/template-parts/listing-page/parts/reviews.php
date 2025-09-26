
<div>

    <!-- Heading -->
    <h2 class="text-22 font-bold mb-5 flex gap-2">Reviews
        <span class="font-normal text-16">(<span id="review-count" hx-swap-oob="outerHTML">0</span>)</span>
        <div id="average-rating" class="flex gap-x-1 text-yellow w-24" hx-swap-oob="outerHTML">
            <?php echo get_template_part('template-parts/global/ratings/rating-stars', '', [ 'rating' => 0, ]); ?>
        </div>
    </h2>

    <!-- Reviews -->
    <div class="bg-white px-6 py-8 lg:px-8 text-center"
        hx-get="<?php echo site_url('/wp-html/v1/reviews/listing/' . $args['post_id']); ?>"
        hx-trigger="load"
    ></div>


</div>
