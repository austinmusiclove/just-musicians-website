<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem]" x-show="showLocationSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-8">Where is your event?</h2>

    <p><span class="text-red">* </span>Zip Code</p>
    <input type="text" name="inquiry_zip_code" x-ref="inquiryZipCodeInput"
        autocomplete="postal-code"
        maxlength="5"
        placeholder="Your event zip code"
        x-model="inquiryZipCode"
        :class="{ 'shake': shakeElements.has('inquiryZipCode') }"
    />

    <div class="absolute bottom-10 right-10 flex flex-row gap-1">
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
