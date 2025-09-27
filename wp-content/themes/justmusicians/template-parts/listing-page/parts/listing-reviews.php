
<div class="w-full">

    <!-- Heading -->
    <h2 class="text-22 font-bold mb-5 flex gap-2">Reviews
        <span class="font-normal text-16">(<span id="review-count" hx-swap-oob="outerHTML">0</span>)</span>
        <div id="average-rating" class="flex gap-x-1 text-yellow w-24" hx-swap-oob="outerHTML"></div>
    </h2>

    <!-- Reviews -->
    <div
        hx-get="<?php echo site_url('/wp-html/v1/reviews/listing/' . $args['post_id']); ?>"
        hx-trigger="load"
    ></div>


</div>
