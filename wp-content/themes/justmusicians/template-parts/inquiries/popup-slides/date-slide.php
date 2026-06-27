<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem] max-h-[60vh] flex flex-col overflow-y-auto pr-2" x-show="showDateSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-8">When is your event?</h2>

    <span>
        <p><span class="text-red">* </span>Date</p>
        <input id="inquiry-start-date-input" type="date" name="event_start_date" class="mb-4"
            :class="{ 'shake': shakeElements.has('inquiry-start-date-input') }"
            x-model="inquiryStartDate"
            x-ref="inquiryStartDateInputElm"
            :min="new Date(Date.now() - new Date().getTimezoneOffset() * 60000).toISOString().split('T')[0]"
        >

        <span x-show="inquiryStartDate" x-cloak>
            <p>Start Time</p>
            <input type="time" name="event_start_time" class="mb-4">
        </span>

        <span x-show="inquiryStartDate" x-cloak>
            <p>End Time</p>
            <input type="time" name="event_end_time" class="mb-4">
        </span>

    </span>

    <div class="mt-auto pt-6 flex justify-end gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2"
            x-show="inquiryListing && accountSettings.has_events" x-cloak
            x-on:click="_showInquirySlide('request')"
        >Back</button>
        <button type="button" class="shadow-black-offset border-2 border-black font-sun-motter text-16 px-4 py-2 bg-grey text-white cursor-not-allowed"
            x-show="!inquiryStartDate" x-cloak
            x-on:click="_emphasizeElm($refs.inquiryStartDateInputElm, 'inquiry-start-date-input')"
        >Next</button>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black text-white font-sun-motter text-16 px-4 py-2"
            x-show="inquiryStartDate" x-cloak
            x-on:click="_showInquirySlide('location')"
        >Next</button>
    </div>

</div>
