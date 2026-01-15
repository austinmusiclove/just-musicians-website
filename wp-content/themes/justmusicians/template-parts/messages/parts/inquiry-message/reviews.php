<div class="w-full">


    <!-- Heading -->
    <a x-bind:href="'<?php echo site_url(); ?>' + '/buyers/' + message.sender_id" target="_blank">

        <h2 class="text-14 font-semibold flex gap-2">
            <span class="opacity-50 hover:opacity-100">Read Reviews</span>
            <span class="font-normal text-14">(<span x-text="message.sender_review_count"></span>)</span>
            <?php echo get_template_part('template-parts/reviews/rating-stars-display-inquiry-message', '', []); ?>
        </h2>

    </a>


</div>
