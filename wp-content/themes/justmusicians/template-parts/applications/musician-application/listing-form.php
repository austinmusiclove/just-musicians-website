<div class="flex flex-col gap-8" data-testid="listing-form">
    <?php echo get_template_part('template-parts/listing-form/basic-info', '', []); ?>
    <?php echo get_template_part('template-parts/global/form/tag-select-input', '', [
        'id'         => 'genres-input',
        'label'      => 'Genres',
        'input_name' => 'genres',
        'options'    => get_terms_decoded('genre', 'names', false, true),
        'x-model'    => 'genresCheckboxes',
        'tooltip'    => "Select at least one genre",
    ]); ?>
    <?php echo get_template_part('template-parts/listing-form/contact', '', []); ?>
    <?php echo get_template_part('template-parts/listing-form/media', '', []); ?>
</div>
