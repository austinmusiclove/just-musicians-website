<div data-slide="2" class="slide w-[32rem] pb-8 grow" x-show="showSlide2" x-cloak>

    <div class="progress-tracker bg-yellow h-2 w-64 absolute top-0 left-0"></div>


    <h2 class="font-bold font-poppins text-20 mb-8">Any details you’d like to add?</h2>

    <form>
        <textarea class="w-full h-40"></textarea>
    </form>

    <div class="absolute bottom-10 right-10 flex flex-row gap-1">
        <button type="button" data-trigger="slide-1" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="showSlide1 = true; showSlide2 = false;">Back</button>
        <button type="button" data-trigger="slide-3" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2" x-on:click="showSlide3 = true; showSlide2 = false;">Skip</button>
    </div>

</div>


