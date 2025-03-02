<div>
<?php
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'genre-filters',
        'title' => 'Genre',
        'input_name' => 'genres', // should match the input name used for the tag check boxes
        'tag_1' => 'Folk',
        'tag_2' => 'Hip Hop/Rap',
        'tag_3' => 'Latin',
        'tag_4' => 'Soul/RnB',
        'tag_1_selected' => false,
        'tag_2_selected' => false,
        'tag_3_selected' => false,
        'tag_4_selected' => false,
        'alpine_modal_var' => 'showGenreModal'
    ));
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'type-filters',
        'title' => 'Type',
        'input_name' => 'types', // should match the input name used for the tag check boxes
        'tag_1' => 'Band',
        'tag_2' => 'DJ',
        'tag_3' => 'Musician',
        'tag_4' => 'Artist',
        'tag_1_selected' => false,
        'tag_2_selected' => false,
        'tag_3_selected' => false,
        'tag_4_selected' => false,
        'alpine_modal_var' => 'showTypeModal'
    ));
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'id' => 'category-filters',
        'title' => 'Category',
        'input_name' => 'tags', // should match the input name used for the tag check boxes
        'tag_1' => 'Acoustic',
        'tag_2' => 'Cover Band',
        'tag_3' => 'Wedding Band',
        'tag_4' => 'Background Music',
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
