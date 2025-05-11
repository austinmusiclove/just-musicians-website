<div data-slide="1" class="slide w-[32rem] pb-8 grow" x-show="showLocationSlide" x-cloak>

    <div class="progress-tracker bg-yellow h-2 w-24 absolute top-0 left-0"></div>


        <h2 class="font-bold font-poppins text-20 mb-8">Where is your event?</h2>

        <input type="text" name="event_zipcode"
            maxlength="5"
            pattern="^\d{5}(-\d{4})?$"
            title="Enter a valid ZIP code (e.g., 12345 or 12345-6789)."
            placeholder="Your event zip code"
            x-model="inquiryZipCode"
        />

    <div class="absolute bottom-10 right-10 flex flex-row gap-1">
        <button type="button" data-trigger="slide-1" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('date')">Back</button>
        <button type="button" data-trigger="slide-2" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2"
            x-text="inquiryZipCode ? 'Next' : 'Skip'"
            x-on:click="_showInquirySlide('details')"
        >Next</button>
    </div>

</div>
