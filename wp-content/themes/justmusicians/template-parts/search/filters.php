<div>

    <div class="border-b border-black/20 mb-6 pb-6 last:mb-0 last:pb-0 last:border-b-0">
        <h3 class="font-bold text-18 mb-3">Verification</h3>
        <?php echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Verified only',
            'name' => 'verified',
            'value' => 'Verified',
            'x-model' => 'verifiedCheckbox',
            'on_change_event' => 'filterupdate',
        )); ?>
    </div>

<?php
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'category-filters',
        'title' => 'Categories',
        'input_name' => 'categories', // should match the input name used for the tag modal checkboxes
        'default_tags' => get_default_options('category'),
        'tags' => isset($args['categories']) ? $args['categories'] : [],
        'show_modal_var' => 'showCategoryModal',
        'x-model' => 'categoriesCheckboxes',
    ));
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'genre-filters',
        'title' => 'Genre',
        'input_name' => 'genres', // should match the input name used for the tag modal checkboxes
        'default_tags' => get_default_options('genre'),
        'tags' => isset($args['genres']) ? $args['genres'] : [],
        'show_modal_var' => 'showGenreModal',
        'x-model' => 'genresCheckboxes',
    ));
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'subgenre-filters',
        'title' => 'Sub Genre',
        'input_name' => 'subgenres', // should match the input name used for the tag modal checkboxes
        'default_tags' => get_default_options('subgenre'),
        'tags' => isset($args['subgenres']) ? $args['subgenres'] : [],
        'show_modal_var' => 'showSubGenreModal',
        'x-model' => 'subgenresCheckboxes',
    ));
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'instrumentation-filters',
        'title' => 'Instrumentation',
        'input_name' => 'instrumentations', // should match the input name used for the tag modal checkboxes
        'default_tags' => get_default_options('instrumentation'),
        'tags' => isset($args['instrumentations']) ? $args['instrumentations'] : [],
        'show_modal_var' => 'showInstrumentationModal',
        'x-model' => 'instrumentationsCheckboxes',
    ));
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'setting-filters',
        'title' => 'Settings',
        'input_name' => 'settings', // should match the input name used for the tag modal checkboxes
        'default_tags' => get_default_options('setting'),
        'tags' => isset($args['settings']) ? $args['settings'] : [],
        'show_modal_var' => 'showSettingModal',
        'x-model' => 'settingsCheckboxes',
    ));
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'ensemble-size-filters',
        'title' => 'Ensemble Size',
        'input_name' => 'ensemble_size', // should match the input name used for the tag modal checkboxes
        'default_tags' => get_default_options('ensemble_size'),
        'tags' => [],
        'show_modal_var' => 'showEnsembleSizeModal',
        'x-model' => 'ensembleSizeCheckboxes',
    ));
    /*
    echo get_template_part('template-parts/filters/elements/ensemble-size-input', '', [
        'min_value'         => 1,
        'max_value'         => 10,
        'min_input_name'    => 'min_ensemble_size',
        'max_input_name'    => 'max_ensemble_size',
        'min_input_x_model' => 'minEnsembleSize',
        'max_input_x_model' => 'maxEnsembleSize',
        'min_input_x_ref'   => 'minEnsembleSize',
        'max_input_x_ref'   => 'maxEnsembleSize',
        'on_change_event'   => 'filterupdate',
    ]);
    */
    //echo get_template_part('template-parts/filters/location', '', array());

    ?>
</div>
