<div class="flex flex-col gap-4" x-show="!showEditForm">

    <?php echo get_template_part('template-parts/events/event-details/date-time'); ?>

    <?php echo get_template_part('template-parts/events/event-details/location', '', [
        'address_line_1' => $args['address_line_1'] ?? '',
        'address_line_2' => $args['address_line_2'] ?? '',
        'city'           => $args['city'] ?? '',
        'state'          => $args['state'] ?? '',
        'zip_code'       => $args['zip_code'] ?? '',
    ]); ?>

    <?php echo get_template_part('template-parts/events/event-details/genre', '', [
        'genres'         => $args['genres'] ?? [],
    ]); ?>

    <?php echo get_template_part('template-parts/events/event-details/ensemble_size', '', [
        'ensemble_size'  => $args['ensemble_size'] ?? [],
    ]); ?>

    <?php echo get_template_part('template-parts/events/event-details/details', '', []); ?>

    <?php echo get_template_part('template-parts/events/event-details/compensation', '', [
        'budget'         => $args['budget'] ?? '',
        'compensation'   => $args['compensation'] ?? '',
    ]); ?>

    <!-- Applicant Requirements -->
    <?php echo get_template_part('template-parts/events/event-details/requirements', '', [
        'request_quote'   => $args['request_quote'] ?? false,
        'request_draw'    => $args['request_draw'] ?? false,
    ]); ?>

    <!-- Edit Button -->
    <div class="pt-4">
        <button type="button" x-on:click="showEditForm = true" class="bg-yellow hover:bg-navy text-black hover:text-white px-6 py-3 rounded-sm font-sun-motter text-14 w-fit whitespace-nowrap inline-block">
            Edit Event
        </button>
    </div>

</div>

<!-- Edit Event Form -->
<div x-show="showEditForm">
    <?php echo get_template_part('template-parts/events/event-details/edit-event-form', '', []); ?>
</div>

