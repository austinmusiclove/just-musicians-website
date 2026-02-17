<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem] max-h-[60vh] overflow-y-auto pr-2" x-show="showReviewSlide" x-cloak>


    <!-- Heading -->
    <h2 class="font-bold font-poppins text-20 mb-8 sm:mb-16">Tell us about your experience with <span x-text="revieweeName"></span></h2>
    <input type="hidden" name="reviewee_name" x-model="revieweeName" />


    <!-- Rating Stars Input -->
    <div class="flex gap-x-1 text-yellow w-48 sm:w-64 my-4 sm:my-8"
        :class="{ 'shake': shakeElements.has('reviewRatingInput') }"
        x-ref="reviewRating"
    >
        <div
            class="flex gap-x-1 text-yellow w-48 sm:w-64 my-4 sm:my-8 cursor-pointer"
            x-on:mouseleave="mouseoverRating = 0"
        >
            <?php for ($rating_val = 1; $rating_val <= 5; $rating_val++) {
                echo get_template_part('template-parts/reviews/stars/interactive-rating-star', '', [ 'rating_val' => $rating_val ]);
            } ?>
        </div>

        <input type="hidden" name="rating" id="rating-input" x-model="rating">
    </div>


    <!-- Review Body -->
    <p class="text-14"><span class="text-red">* </span>At least 50 characters (<span x-text="reviewBody.length">0</span>/50)</p>
    <textarea class="w-full h-48 sm:h-64 mb-6" name="review_body"
        x-bind:placeholder="`Tell us about ${revieweeName} in at least 50 characters`"
        :class="{ 'shake': shakeElements.has('reviewBodyInput') }"
        x-model="reviewBody"
        x-ref="reviewBody"
    ></textarea>


    <!-- Submit Button -->
    <div class="mt-4 flex flex-row justify-end gap-1">

        <!-- If rating has been selected and body is long enough, show submit button; else show disabled button that waggles the required input -->
        <button type="button" class="shadow-black-offset border-2 border-black font-sun-motter text-16 px-4 py-2 bg-grey text-white cursor-not-allowed"
            x-show="rating == 0" x-cloak
            x-on:click.prevent="_emphasizeElm($refs.reviewRating, 'reviewRatingInput')"
        >Submit</button>
        <button type="button" class="shadow-black-offset border-2 border-black font-sun-motter text-16 px-4 py-2 bg-grey text-white cursor-not-allowed"
            x-show="rating > 0 && reviewBody.length < 50" x-cloak
            x-on:click.prevent="_emphasizeElm($refs.reviewBody, 'reviewBodyInput')"
        >Submit</button>
        <button type="submit" class="review-submit-button flex justify-center items-center bg-navy shadow-black-offset border-2 border-black text-white font-sun-motter text-16 px-4 py-2 w-[82px] h-[40px] disabled:opacity-50"
            x-show="rating > 0 && reviewBody.length >= 50" x-cloak
        >
            <span class="htmx-indicator-component-block-replace">Submit</span>
            <span class="htmx-indicator-component-block">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
            </span>
        </button>

    </div>

</div>
