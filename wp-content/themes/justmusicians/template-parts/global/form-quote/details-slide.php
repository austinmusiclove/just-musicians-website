<div data-slide="2" class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem]" x-show="showDetailsSlide" x-cloak >


    <h2 class="font-bold font-poppins text-20 mb-8">Did we leave anything out?</h2>
    <p class="text-16 mb-8">Enter any other relevant details about your event that a musician would need to give you a quote.</p>

    <p class="text-14">At least 25 characters (<span x-text="inquiryDetails.length">0</span>/25)</p>
    <textarea class="w-full h-40 mb-6" name="inquiry_details" x-model="inquiryDetails" x-ref="inquiryDetails"></textarea>

    <div class="absolute bottom-10 right-10 flex flex-row gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('equipment')">Back</button>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2 disabled:bg-grey disabled:text-white"
            x-show="inquiryListing" x-cloak
            x-on:click="_showInquirySlide('quotes')"
        >Next</button>
        <button type="submit" class="relative bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2 disabled:bg-grey disabled:text-white inquiry-submit-button"
            x-show="!inquiryListing" x-cloak
            x-bind:disabled="inquiryDetails.length < 25"
        >
            <span class="htmx-indicator-replace">Submit</span>
            <span class="absolute inset-0 flex items-center justify-center htmx-indicator">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
            </span>
        </button>
    </div>

</div>
