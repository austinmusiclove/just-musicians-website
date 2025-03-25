<div>
<?php
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'type-filters',
        'title' => 'Listing Type',
        'input_name' => 'types', // should match the input name used for the tag modal checkboxes
        'tag_1' => get_default_option('type', 0),
        'tag_2' => get_default_option('type', 1),
        'tag_3' => get_default_option('type', 2),
        'tag_4' => get_default_option('type', 3),
        'tag_1_selected' => false,
        'tag_2_selected' => false,
        'tag_3_selected' => false,
        'tag_4_selected' => false,
        'alpine_modal_var' => 'showTypeModal'
    ));
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'genre-filters',
        'title' => 'Genre',
        'input_name' => 'genres', // should match the input name used for the tag modal checkboxes
        'tag_1' => get_default_option('genre', 0),
        'tag_2' => get_default_option('genre', 1),
        'tag_3' => get_default_option('genre', 2),
        'tag_4' => get_default_option('genre', 3),
        'tag_1_selected' => false,
        'tag_2_selected' => false,
        'tag_3_selected' => false,
        'tag_4_selected' => false,
        'alpine_modal_var' => 'showGenreModal'
    ));
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'subgenre-filters',
        'title' => 'Sub Genre',
        'input_name' => 'subgenres', // should match the input name used for the tag modal checkboxes
        'tag_1' => get_default_option('subgenre', 0),
        'tag_2' => get_default_option('subgenre', 1),
        'tag_3' => get_default_option('subgenre', 2),
        'tag_4' => get_default_option('subgenre', 3),
        'tag_1_selected' => false,
        'tag_2_selected' => false,
        'tag_3_selected' => false,
        'tag_4_selected' => false,
        'alpine_modal_var' => 'showSubGenreModal'
    ));
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'instrumentation-filters',
        'title' => 'Instrumentation',
        'input_name' => 'instrumentations', // should match the input name used for the tag modal checkboxes
        'tag_1' => get_default_option('instrumentation', 0),
        'tag_2' => get_default_option('instrumentation', 1),
        'tag_3' => get_default_option('instrumentation', 2),
        'tag_4' => get_default_option('instrumentation', 3),
        'tag_1_selected' => false,
        'tag_2_selected' => false,
        'tag_3_selected' => false,
        'tag_4_selected' => false,
        'alpine_modal_var' => 'showInstrumentationModal'
    ));
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'setting-filters',
        'title' => 'Settings',
        'input_name' => 'settings', // should match the input name used for the tag modal checkboxes
        'tag_1' => get_default_option('setting', 0),
        'tag_2' => get_default_option('setting', 1),
        'tag_3' => get_default_option('setting', 2),
        'tag_4' => get_default_option('setting', 3),
        'tag_1_selected' => false,
        'tag_2_selected' => false,
        'tag_3_selected' => false,
        'tag_4_selected' => false,
        'alpine_modal_var' => 'showSettingModal'
    ));
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'tag-filters',
        'title' => 'Other Categories',
        'input_name' => 'tags', // should match the input name used for the tag modal checkboxes
        'tag_1' => get_default_option('tag', 0),
        'tag_2' => get_default_option('tag', 1),
        'tag_3' => get_default_option('tag', 2),
        'tag_4' => get_default_option('tag', 3),
        'tag_1_selected' => false,
        'tag_2_selected' => false,
        'tag_3_selected' => false,
        'tag_4_selected' => false,
        'alpine_modal_var' => 'showTagModal'
    ));
    //echo get_template_part('template-parts/filters/location', '', array());

    ?>

    <div class="border-b border-black/20 mb-6 pb-6 last:mb-0 last:pb-0 last:border-b-0">
        <h3 class="font-bold text-18 mb-3">Verification</h3>
        <?php echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Verified only',
            'name' => 'verified',
            'value' => 'Verified',
            'x-model' => 'verifiedCheckbox',
        )); ?>
    </div>
</div>
