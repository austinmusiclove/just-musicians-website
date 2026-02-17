<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem] max-h-[60vh] flex flex-col overflow-y-auto pr-2" x-data="{ inquiryDuration: 0 }" x-show="showDurationSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-8">How many hours of performance time do you need?</h2>
    <p class="text-16 mt-4 mb-8">If you are booking for multiple performances, this is the number of hours per performance.</p>

    <input type="number" name="inquiry_duration" step=".25" x-model="inquiryDuration" />

    <div class="mt-auto pt-6 flex justify-end gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('location')">Back</button>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2 disabled:bg-grey disabled:text-white"
            x-text="inquiryDuration ? 'Next' : 'Skip'"
            x-on:click="_showInquirySlide('genre')"
        >Next</button>
    </div>

</div>

