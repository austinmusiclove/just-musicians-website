<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem]" x-show="showLocationSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-8">Where is your event?</h2>

    <p>Zip Code <span class="text-red">*</span></p>
    <input type="text" name="event_zipcode" x-ref="inquiryZipCodeInput"
        autocomplete="postal-code"
        maxlength="5"
        placeholder="Your event zip code"
        x-model="inquiryZipCode"
    />

    <p class="text-16 mt-8">Include any details you'd like about the location(s).</p>
    <textarea class="w-full h-40 mb-6" name="inquiry_location_details" x-model="inquiryLocationDetails"></textarea>

    <div class="absolute bottom-10 right-10 flex flex-row gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('date')">Back</button>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2 disabled:bg-grey disabled:text-white"
            x-bind:disabled="inquiryZipCode.length < 5"
            x-text="inquiryZipCode || inquiryLocationDetails ? 'Next' : 'Skip'"
            x-on:click="_showInquirySlide('duration')"
        >Next</button>
    </div>

</div>
