<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem] max-h-[60vh] flex flex-col overflow-y-auto pr-2" x-data="{ numPerformers: [] }" x-show="showPerformersSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-8">How many performers do you need?</h2>
    <p class="text-16 mt-4 mb-8">Check any and all ensemble sizes you would consider for your event.</p>


    <div class="h-64">
        <input type="hidden" name="inquiry_ensemble_size[]" >
        <?php
        $ensemble_sizes = get_default_options('ensemble_size');
        echo get_template_part('template-parts/search/filter-components/taxonomy-options', '', [
            'terms'           => $ensemble_sizes,
            'input_name'      => 'event_ensemble_size',
            'input_x_model'   => 'numPerformers',
            'show_search_bar' => false,
        ]); ?>
    </div>


    <div class="mt-auto pt-6 flex justify-end gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('genre')">Back</button>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2 disabled:bg-grey disabled:text-white"
            x-text="numPerformers.length > 0 ? 'Next' : 'Skip'"
            x-on:click="_showInquirySlide('details')"
        >Next</button>
    </div>

</div>

