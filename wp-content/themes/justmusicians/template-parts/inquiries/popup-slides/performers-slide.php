<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem] max-h-[60vh] flex flex-col overflow-y-auto pr-2" x-data="{ numPerformers: [] }" x-show="showPerformersSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-8">How many performers do you need?</h2>
    <p class="text-16 mt-4 mb-8">Check any and all ensemble sizes you would consider for your event.</p>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 gap-y-2 gap-x-10 custom-checkbox max-h-[500px] md:max-h-[240px]">
        <input type="hidden" name="inquiry_ensemble_size[]" >

        <!-- Select all -->
        <label class="custom-checkbox has-disabled:text-grey">
        <input type="checkbox" value="any"
            x-on:change="
                if ($event.target.checked) {
                  numPerformers = ['1','2','3','4','5','6','7','8','9','10+']; // select all
                } else {
                  numPerformers = []; // deselect all
                }
            "
        />
        <span class="checkmark"></span>Select All</label>

        <!-- Ensemble size options -->
        <?php $ensemble_size_options = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10+"];
        foreach ($ensemble_size_options as $option) {
            echo get_template_part('template-parts/filters/elements/checkbox', '', [
                'label'    => $option,
                'value'    => $option,
                'name'     => 'inquiry_ensemble_size',
                'is_array' => true,
                'x-model'  => 'numPerformers',
                'x-ref'    => 'inquiryEnsembleSize_' . $option,
            ]);
        } ?>

    </div>


    <div class="mt-auto pt-6 flex justify-end gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('genre')">Back</button>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2 disabled:bg-grey disabled:text-white"
            x-text="numPerformers.length > 0 ? 'Next' : 'Skip'"
            x-on:click="_showInquirySlide('details')"
        >Next</button>
    </div>

</div>

