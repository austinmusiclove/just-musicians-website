
<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem]" x-show="showDateSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-8">When is your event?</h2>

    <fieldset class="radio-buttons custom-radio">
        <div>
            <input type="radio" id="single_date" name="inquiry_date_type" x-model="inquiryDateType" value="Single Date">
            <span></span>
            <label class="text-16" for="single_date">I already have a set date for my event</label>
        </div>
        <div>
            <input type="radio" id="multi_date" name="inquiry_date_type" x-model="inquiryDateType" value="Multiple Dates">
            <span></span>
            <label class="text-16" for="multi_date">I am booking live music for multiple dates</label>
        </div>
        <div>
            <input type="radio" id="tbd_date" name="inquiry_date_type" x-model="inquiryDateType" value="TBD" checked>
            <span></span>
            <label class="text-16" for="tbd_date">TBD</label>
        </div>
    </fieldset>

    <span x-show="inquiryDateType == 'Single Date'" x-cloak>
        <p>Date</p>
        <input type="date" name="inquiry_date" class="mb-4">

        <p>Performance Time</p>
        <input type="time" name="inquiry_time" class="mb-4">
    </span>

    <div class="absolute bottom-10 right-10 flex flex-row gap-1">
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2"
            x-text="inquiryDateType ? 'Next' : 'Skip'"
            x-on:click="_showInquirySlide('location')"
        >Next</button>
    </div>

</div>
