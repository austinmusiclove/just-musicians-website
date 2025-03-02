<div data-slide="5" class="slide w-[24rem] text-center" x-show="showSlide5" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-4">You’re almost done!</h2>

    <p class="text-18 mb-6">Are you sure you want to leave now and lose your progress?</p>

    <div class="flex flex-row gap-1 justify-center">
        <button data-popup="quote" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2"
            x-on:click="
                showInquiryModal = false;
                showSlide1 = false;
                showSlide2 = false;
                showSlide3 = false;
                showSlide4 = false;
                showSlide5 = false;
            "
            >
        Discard</button>
        <!-- TODO somehow go to the slide that the user was on before trying to exit -->
        <button data-trigger="slide-4" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2" x-on:click="showSlide1 = true;">Continue</button>
    </div>

</div>


