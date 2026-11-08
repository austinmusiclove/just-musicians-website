<form id="event-applicants-form"
    x-bind:hx-get="'<?php echo site_url('/wp-html/v1/events/'); ?>' + eventId + '/applicants/'"
    hx-target="#applicant-results"
    hx-indicator=".spinner-start"
    hx-trigger="intersect once, filterupdate"
    x-data='{ showSuggestions: false, }'
>

    <div class="flex flex-wrap items-center gap-2 mb-4 pb-4">
        <div x-on:filter_status-changed="$dispatch('filterupdate');">
            <?php get_template_part('template-parts/global/form/dropdown', '', [
                'options'     => [
                    ['value' => 'all',         'label' => 'All Musicians'],
                    ['value' => 'response',    'label' => 'All Responses'], // available and unavailable
                    ['value' => 'available',   'label' => 'Available'],
                    ['value' => 'unavailable', 'label' => 'Unavailable'],
                    ['value' => 'request',     'label' => 'Awaiting Response'], // inquiry and stale
                ],
                'input_name'  => 'filter_status',
                'selected'    => 'all',
            ]); ?>
        </div>

        <?php
        $sort_options = [
            ['value' => 'recent',      'label' => 'Most Recent'],
            ['value' => 'high-rating', 'label' => 'Highest Rating'],
        ];

        if (get_field('request_quote')) {
            $sort_options[] = ['value' => 'high-quote', 'label' => 'Highest Quote'];
            $sort_options[] = ['value' => 'low-quote',  'label' => 'Lowest Quote'];
        }

        if (get_field('request_draw')) {
            $sort_options[] = ['value' => 'high-draw',  'label' => 'Highest Draw'];
            $sort_options[] = ['value' => 'low-draw',   'label' => 'Lowest Draw'];
        }
        ?>

        <div x-on:sort-changed="$dispatch('filterupdate');">
            <?php get_template_part('template-parts/global/form/dropdown', '', [
                'options'     => $sort_options,
                'input_name'  => 'sort',
                'selected'    => 'recent',
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
