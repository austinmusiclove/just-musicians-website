<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem]" x-data="{ quotesRequested: '' }" x-show="showQuoteSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-8">Would you like to get competing quotes?</h2>
    <p class="text-16 mb-4">Your inquiry will be sent to <span x-text="inquiryListingName"></span>.</p>
    <p class="text-16 mb-12">Would you like to get competing quotes? You can always request more quotes later and you can always manually request a quote from a musician for this inquiry once it is submitted.</p>

    <fieldset class="radio-buttons custom-radio">
        <div>
            <input type="radio" id="send-me-quotes" name="inquiry_quotes_requested" value="5" x-model="quotesRequested">
            <span></span>
            <label for="send-me-quotes">Yes, please send this inquiry to other similar musicians.</label>
        </div>
        <div>
            <input type="radio" id="manual-quotes" name="inquiry_quotes_requested" value="1" x-model="quotesRequested">
            <span></span>
            <label for="manual-quotes">No, I'd like to manually select the musicians who can submit a quote.</label><br>
        </div>
    </fieldset>

    <div class="absolute bottom-10 right-10 flex flex-row gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('details')">Back</button>
        <button type="submit" class="relative bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2 disabled:opacity-50 inquiry-submit-button"
            x-bind:disabled="!quotesRequested"
        >
            <span class="htmx-indicator-replace">Submit</span>
            <span class="absolute inset-0 flex items-center justify-center htmx-indicator">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
            </span>
        </button>
    </div>

</div>
