<div id="event-form-taxonomy" class="flex flex-col gap-4 mb-4">
    <div>
        <label class="text-14 font-bold">Desired Genres</label>
        <p class="text-red text-12 mt-1" x-show="genres.length < 1" x-cloak>Please select at least one genre</p>
        <?php
        $genres = get_terms_decoded('genre', 'names');
        echo get_template_part('template-parts/search/filter-components/taxonomy-options', '', [
            'terms'           => $genres,
            'input_name'      => 'event_genres',
            'input_x_model'   => 'genres',
            'show_search_bar' => false,
        ]); ?>
    </div>
    <div>
        <label class="text-14 font-bold">Desired Ensemble Size</label>
        <?php
        $ensemble_sizes = get_default_options('ensemble_size');
        echo get_template_part('template-parts/search/filter-components/taxonomy-options', '', [
            'terms'           => $ensemble_sizes,
            'input_name'      => 'event_ensemble_size',
            'input_x_model'   => 'ensembleSize',
            'show_search_bar' => false,
        ]); ?>
    </div>
</div>
