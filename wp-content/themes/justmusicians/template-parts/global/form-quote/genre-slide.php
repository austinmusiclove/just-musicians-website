<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem]" x-data="{ inquiryGenres: [] }" x-show="showGenreSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-8">What genre of music is appropriate for your event?</h2>
    <p class="text-16 mt-4 mb-8">Choose at least one <span class="text-red">*</span></p>
    <?php
    $genres = get_terms_decoded('genre', 'names');
    echo get_template_part('template-parts/filters/taxonomy-options', '', [
        'terms' => $genres,
        'input_name' => 'inquiry_genres',
        'input_x_model' => 'inquiryGenres',
    ]); ?>

    <div class="absolute bottom-10 right-10 flex flex-row gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('duration')">Back</button>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2 disabled:bg-grey disabled:text-white"
            x-bind:disabled="inquiryGenres.length < 1"
            x-on:click="_showInquirySlide('performers')"
        >Next</button>
    </div>

</div>
