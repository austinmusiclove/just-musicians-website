<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem] max-h-[60vh] flex flex-col overflow-y-auto pr-2" x-show="showLocationSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-8">Where is your event?</h2>

    <p><span class="text-red">* </span>Zip Code</p>
    <input type="text" name="inquiry_zip_code"
        autocomplete="postal-code"
        maxlength="5"
        placeholder="Your event zip code"
        :class="{ 'shake': shakeElements.has('inquiryZipCode') }"
        x-ref="inquiryZipCodeInput"
        x-model="inquiryZipCode"
        x-on:input="inquiryZipCode = inquiryZipCode.replace(/\D/g, '')"
        x-on:keydown.enter.prevent
    />

    <div class="mt-auto pt-6 flex justify-end gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('date')">Back</button>
        <button type="button" class="shadow-black-offset border-2 border-black font-sun-motter text-16 px-4 py-2 bg-grey text-white cursor-not-allowed"
            x-show="inquiryZipCode.length < 5" x-cloak
            x-text="inquiryZipCode ? 'Next' : 'Skip'"
            x-on:click="_emphasizeElm($refs.inquiryZipCodeInput, 'inquiryZipCode')"
        >Next</button>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black text-white font-sun-motter text-16 px-4 py-2"
            x-show="inquiryZipCode.length >= 5" x-cloak
            x-text="inquiryZipCode ? 'Next' : 'Skip'"
            x-on:click="_showInquirySlide('budget')"
        >Next</button>
    </div>

</div>
