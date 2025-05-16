<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem]" x-data="{ numPerformers: [] }" x-show="showPerformersSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-8">How many performers do you need?</h2>
    <p class="text-16 mt-4 mb-8">Check any and all ensemble sizes you would consider for your event. If you are booking for multiple performances, this is the number of performers you need per performance.</p>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 gap-y-2 gap-x-10 custom-checkbox overflow-scroll max-h-[500px] md:max-h-[240px]">
        <input type="hidden" name="inquiry_ensemble_size[]" >
        <?php $ensemble_size_options = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10+"];
        foreach ($ensemble_size_options as $option) {
            echo get_template_part('template-parts/filters/elements/checkbox', '', [
                'label' => $option,
                'value' => $option,
                'name' => 'inquiry_ensemble_size',
                'x-model' => 'numPerformers',
                'is_array' => true,
            ]);
        } ?>
    </div>


    <div class="absolute bottom-10 right-10 flex flex-row gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('genre')">Back</button>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2 disabled:bg-grey disabled:text-white"
            x-text="numPerformers.length > 0 ? 'Next' : 'Skip'"
            x-on:click="_showInquirySlide('equipment')"
        >Next</button>
    </div>

</div>

