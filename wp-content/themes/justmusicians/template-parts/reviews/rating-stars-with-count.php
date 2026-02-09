<div class="flex gap-2">

    <div class="flex gap-x-1 text-yellow w-24">
        <?php echo get_template_part('template-parts/reviews/rating-stars-display', '', [ 'rating' => $args['rating'], ]); ?>
    </div>

    <span class="font-normal text-12">
        (<?php echo $args['review_count']; ?>
        <?php echo ( (int) $args['review_count'] === 1 ) ? 'review' : 'reviews'; ?>)
    </span>

</div>
