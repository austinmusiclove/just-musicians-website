<div data-slide="1" class="slide w-[32rem] pb-8 grow" x-show="showDateSlide" x-cloak>

    <div class="progress-tracker bg-yellow h-2 w-24 absolute top-0 left-0"></div>


        <h2 class="font-bold font-poppins text-20 mb-8">When is your event?</h2>

        <input type="date" name="event_date" x-bind:class="{'text-grey': !inquiryDate, 'text-black': inquiryDate}" x-model="inquiryDate">

    <div class="absolute bottom-10 right-10 flex flex-row gap-1">
        <button type="button" data-trigger="slide-2" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2"
            x-text="inquiryDate ? 'Next' : 'Skip'"
            x-on:click="_showInquirySlide('location')"
        >Next</button>
    </div>

</div>
