<div class="popup-wrapper pb-8 pt-28 md:pt-0 w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center" x-show="showReviewModal" x-cloak>
    <!-- TODO need to go to slide 5 if not done or just close it all if done and if got o slide 5 need to remember what slide they were on to return to it -->
    <div class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"
        x-on:click="showReviewModal = false; $refs.reviewForm.reset();"
    ></div>

    <div class="bg-white p-8 md:p-20 relative w-full h-full md:w-auto md:h-auto flex items-center justify-center" style="max-width: 780px;">


        <!-- Progress tracker -->
        <div class="progress-tracker bg-yellow h-2 absolute top-0 left-0 transition-all duration-500"
            x-bind:style="`width: ${reviewProgress}%`"
        ></div>

        <!-- X button -->
        <img class="close-button opacity-60 hover:opacity-100 absolute top-2 right-2 cursor-pointer"
            src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>"
            x-on:click="showReviewModal = false; $refs.reviewForm.reset();"
        />


        <!-- Review Popup Form -->
        <!-- Prevent submitting form with Enter button but allow shift+enter in textarea -->
        <form x-ref="reviewForm"
            hx-post="/wp-html/v1/reviews/"
            hx-target="#review-result"
            hx-ext="disable-element" hx-disable-element=".review-submit-button"
            x-on:keydown.enter="
                if ($event.key === 'Enter' && !($event.target.tagName === 'TEXTAREA' && $event.shiftKey)) {
                    $event.preventDefault();
                }
            "
        >

            <!--
            <input type="hidden" name="review_type" x-model="reviewType" x-ref="reviewTypeInput" />
            <input type="hidden" name="reviewee_id" x-model="revieweeId" x-ref="revieweeIdInput" />
            -->
            <?php echo get_template_part('template-parts/reviews/popup-slides/review-slide',    '', []); ?>
            <?php echo get_template_part('template-parts/reviews/popup-slides/user-info-slide', '', []); ?>
            <?php echo get_template_part('template-parts/reviews/popup-slides/thank-you-slide', '', []); ?>
            <?php echo get_template_part('template-parts/reviews/popup-slides/error-slide',     '', []); ?>


        </form>
        <span id="review-result"></span>


    </div>
</div>
