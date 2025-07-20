<div data-slide="2" class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem]" x-show="showDetailsSlide" x-cloak >


    <h2 class="font-bold font-poppins text-20 mb-8">Your Message to the musicians</h2>
    <p class="text-18 mb-4"><span class="text-red">* </span>Subject Line</p>
    <p class="text-14">At least 15 characters (<span x-text="inquirySubject.length">0</span>/15)</p>
    <input type="text" name="inquiry_subject"
        placeholder="example: Wedding ceremony music in Dripping Springs, TX"
        :class="{ 'shake': shakeElements.has('inquirySubjectInput') }"
        x-model="inquirySubject"
        x-ref="inquirySubject"
    />

    <p class="text-16 mt-8 mb-4">Enter any other relevant details about your event that a musician would need to give you a quote.</p>
    <textarea class="w-full h-40 mb-6" name="inquiry_details"></textarea>

    <div class="absolute bottom-10 right-10 flex flex-row gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('equipment')">Back</button>
        <span x-show="inquiryListing" x-cloak>
            <button type="button" class="shadow-black-offset border-2 border-black font-sun-motter text-16 px-4 py-2 bg-grey text-white cursor-not-allowed"
                x-show="inquirySubject.length < 15" x-cloak
                x-on:click="_emphasizeElm($refs.inquirySubject, 'inquirySubjectInput')"
            >Next</button>
            <button type="button" class="bg-navy shadow-black-offset border-2 border-black text-white font-sun-motter text-16 px-4 py-2"
                x-show="inquirySubject.length >= 15" x-cloak
                x-on:click="_showInquirySlide('quotes')"
            >Next</button>
        </span>

        <span x-show="!inquiryListing" x-cloak>
            <button type="button" class="shadow-black-offset border-2 border-black font-sun-motter text-16 px-4 py-2 bg-grey text-white cursor-not-allowed"
                x-show="inquirySubject.length < 15" x-cloak
                x-on:click.prevent="_emphasizeElm($refs.inquirySubject, 'inquirySubjectInput')"
            >Submit</button>
            <button type="submit" class="bg-navy shadow-black-offset border-2 border-black text-white font-sun-motter text-16 px-4 py-2"
                x-show="inquirySubject.length >= 15" x-cloak
            >
                <span class="htmx-indicator-replace">Submit</span>
                <span class="absolute inset-0 flex items-center justify-center htmx-indicator">
                    <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
                </span>
            </button>
        </span>

    </div>

</div>
