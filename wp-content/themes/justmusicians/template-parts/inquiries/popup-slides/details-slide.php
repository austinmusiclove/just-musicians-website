<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem] max-h-[60vh] flex flex-col overflow-y-auto pr-2" x-show="showDetailsSlide" x-cloak >


    <h2 class="font-bold font-poppins text-20 mb-8">Final details about your event</h2>
    <p class="text-18 mb-4"><span class="text-red">* </span>Event Name</p>
    <p class="text-14">At least 12 characters (<span x-text="inquiryEventName.length">0</span>/12)</p>
    <input type="text" name="event_name"
        placeholder="example: Wedding ceremony music in Dripping Springs, TX"
        :class="{ 'shake': shakeElements.has('inquiryEventNameInput') }"
        x-model="inquiryEventName"
        x-ref="inquiryEventName"
    />

    <p class="text-16 mt-8 mb-4">Enter any other relevant details about your event that a musician would need to give you a quote.</p>
    <textarea class="w-full h-48 mb-6 flex-shrink-0" name="event_details" placeholder="Tell us more.."></textarea>

    <div class="mt-auto pt-6 flex justify-end gap-1">

        <!-- Back button -->
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('performers')">Back</button>

        <!-- If listing has been selected, show next button -->
        <button type="button" class="shadow-black-offset border-2 border-black font-sun-motter text-16 px-4 py-2 bg-grey text-white cursor-not-allowed"
            x-show="inquiryListing && inquiryEventName.length < 12" x-cloak
            x-on:click="_emphasizeElm($refs.inquiryEventName, 'inquiryEventNameInput')"
        >Next</button>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black text-white font-sun-motter text-16 px-4 py-2"
            x-show="inquiryListing && inquiryEventName.length >= 12" x-cloak
            x-on:click="_showInquirySlide('quotes')"
        >Next</button>

        <!-- If no listing has been selected, show submit button -->
        <button type="button" class="shadow-black-offset border-2 border-black font-sun-motter text-16 px-4 py-2 bg-grey text-white cursor-not-allowed"
            x-show="!inquiryListing && inquiryEventName.length < 12" x-cloak
            x-on:click.prevent="_emphasizeElm($refs.inquiryEventName, 'inquiryEventNameInput')"
        >Submit</button>
        <button type="submit" class="flex justify-center items-center bg-navy shadow-black-offset border-2 border-black text-white font-sun-motter text-16 px-4 py-2 w-[82px] h-[40px]"
            x-show="!inquiryListing && inquiryEventName.length >= 12" x-cloak
        >
            <span class="htmx-indicator-component-block-replace">Submit</span>
            <span class="htmx-indicator-component-block">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
            </span>
        </button>

    </div>

</div>
