<div class="flex flex-col gap-4" x-show="!showEditForm">

    <?php echo get_template_part('template-parts/events/event-details/date-time'); ?>
    <?php echo get_template_part('template-parts/events/event-details/location'); ?>
    <?php echo get_template_part('template-parts/events/event-details/genre'); ?>
    <?php echo get_template_part('template-parts/events/event-details/ensemble-size'); ?>
    <?php echo get_template_part('template-parts/events/event-details/details'); ?>
    <?php echo get_template_part('template-parts/events/event-details/compensation'); ?>
    <?php echo get_template_part('template-parts/events/event-details/requirements'); ?>

    <!-- Edit Button -->
    <div class="pt-4 flex gap-2">
        <button type="button" x-on:click="showEditForm = true" class="bg-yellow hover:bg-navy text-black hover:text-white px-6 py-3 rounded-sm font-sun-motter text-14 w-fit whitespace-nowrap inline-block">
            Edit Event
        </button>
        <button type="button" class="bg-white hover:bg-red hover:text-white border border-black/20 hover:border-red px-6 py-3 rounded-sm font-sun-motter text-14 w-fit whitespace-nowrap inline-block"
            hx-delete="<?php echo site_url('/wp-html/v1/events/' . get_the_ID()); ?>"
            hx-confirm="Are you sure you want to delete this event?"
            hx-target="#delete-result"
        >
            Delete Event
            <span id="delete-result"></span>
        </button>
    </div>

</div>

<!-- Edit Event Form -->
<div x-show="showEditForm">
    <?php echo get_template_part('template-parts/events/edit-event-form', '', []); ?>
</div>

