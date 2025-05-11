<div data-slide="5" class="slide w-[24rem] text-center" x-show="showDiscardSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-4">You’re almost done!</h2>

    <p class="text-18 mb-6">Are you sure you want to leave now and lose your progress?</p>

    <div class="flex flex-row gap-1 justify-center">
        <button type="button" data-popup="quote" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2"
            x-on:click="_showInquirySlide(''); _clearInquiryForm();"
            >
        Discard</button>
        <button type="button" data-trigger="slide-4" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide(currentInquirySlide);">Continue</button>
    </div>

</div>
