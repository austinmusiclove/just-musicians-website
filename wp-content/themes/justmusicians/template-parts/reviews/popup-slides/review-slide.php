<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem]" x-show="showReviewSlide" x-cloak >


    <!-- Heading -->
    <h2 class="font-bold font-poppins text-20 mb-8 sm:mb-16">Tell us about your experience with <span x-text="revieweeName"></span></h2>


    <!-- Rating Stars -->
    <div class="flex gap-x-1 text-yellow w-48 sm:w-64 my-4 sm:my-8">
        <?php echo get_template_part('template-parts/reviews/rating-stars-input', '', []); ?>
    </div>


    <!-- Review Body -->
    <p class="text-14"><span class="text-red">* </span>At least 50 characters (<span x-text="reviewBody.length">0</span>/50)</p>
    <textarea class="w-full h-48 sm:h-64 mb-6" name="review_body"
        placeholder="example: Wedding ceremony music in Dripping Springs, TX"
        :class="{ 'shake': shakeElements.has('reviewBodyInput') }"
        x-model="reviewBody"
        x-ref="reviewBody"
    ></textarea>


    <!-- Submit Button -->
    <div class="absolute bottom-10 right-10 flex flex-row gap-1">

        <!-- If no listing has been selected, show submit button -->
        <button type="button" class="shadow-black-offset border-2 border-black font-sun-motter text-16 px-4 py-2 bg-grey text-white cursor-not-allowed"
            x-show="reviewBody.length < 50" x-cloak
            x-on:click.prevent="_emphasizeElm($refs.reviewBody, 'reviewBodyInput')"
        >Submit</button>
        <button type="submit" class="flex justify-center items-center bg-navy shadow-black-offset border-2 border-black text-white font-sun-motter text-16 px-4 py-2 w-[82px] h-[40px]"
            x-show="reviewBody.length >= 50" x-cloak
        >
            <span class="absolute htmx-indicator-replace">Submit</span>
            <span class="absolute flex items-center justify-center htmx-indicator">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
            </span>
        </button>

    </div>

</div>
