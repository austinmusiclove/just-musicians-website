<form id="applicants-form"
    x-bind:hx-get="'<?php echo site_url('/wp-html/v1/applications/'); ?>' + applicationId + '/applicants/'"
    hx-target="#applicant-results"
    hx-indicator=".spinner-start"
    hx-trigger="intersect once, filterupdate"
>

    <div class="flex flex-wrap items-center gap-2 mb-4 pb-4">

        <div x-on:sort-changed="$dispatch('filterupdate');">
            <?php get_template_part('template-parts/global/form/dropdown', '', [
                'options'     => [
                    ['value' => 'recent', 'label' => 'Most Recent'],
                ],
                'input_name'  => 'sort',
                'selected'    => 'recent',
            ]); ?>
        </div>

        <div>
        <?php get_template_part('template-parts/global/export', '', [
            'button_id'    => 'applicants-export-button',
            'options'      => [
                ['endpoint' => site_url('/wp-html/v1/applications/' . get_the_ID() . '/applicants-export/csv/'), 'label' => '.CSV'],
            ],
        ]); ?>
        </div>

        <div class="spinner-start flex items-center justify-center htmx-indicator">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '6', 'color' => 'yellow']); ?>
        </div>

    </div>

    <!-- Results container -->
    <?php get_template_part('template-parts/cards/card-containers/listing-cards-container', '', [ 'id' => 'applicant-results', ]); ?>


    <div id="applicants-spinner-bottom" class="my-8 flex items-center justify-center htmx-indicator">
        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
    </div>

</form>
