<div data-slide="2" class="slide w-[32rem] pb-8 grow"
    x-data="{ inquiryDetails: '' }"
    x-show="showDetailsSlide" x-cloak
>

    <div class="progress-tracker bg-yellow h-2 w-64 absolute top-0 left-0"></div>


    <h2 class="font-bold font-poppins text-20 mb-8">Tell us about your event.</h2>
    <p class="text-16 mb-8">Consider including details like the type of music you'd like, the amount of performers, performance duration, the performance setting, specifics on time and location, and any other relevant information.</p>
    <p class="text-14">At least 100 characters (<span x-text="inquiryDetails.length">0</span>/100)</p>

    <textarea class="w-full h-40 mb-6" name="inquiry_details" x-model="inquiryDetails"></textarea>

    <div class="absolute bottom-10 right-10 flex flex-row gap-1">
        <button type="button" data-trigger="slide-1" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('location')">Back</button>
        <button type="button" data-trigger="slide-3" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2 disabled:bg-grey disabled:text-white"
            x-show="loggedIn" x-cloak
            x-bind:disabled="inquiryDetails.length < 100"
            x-on:click="_showInquirySlide('thankyou');"
        >Submit</button>
        <button type="button" data-trigger="slide-3" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2"
            x-show="!loggedIn"
            x-text="inquiryDetails ? 'Next' : 'Skip'"
            x-cloak x-on:click="_showInquirySlide('email');"
        >Next</button>
    </div>

</div>
