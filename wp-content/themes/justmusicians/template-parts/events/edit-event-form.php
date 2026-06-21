<form
    x-bind:hx-post="'<?php echo site_url('/wp-html/v1/events/'); ?>' + event_id"
    hx-target="#event-update-result"
    hx-indicator="#submit-button-content"
>
    <?php echo get_template_part('template-parts/events/event-form/date-time-inputs'); ?>
    <?php echo get_template_part('template-parts/events/event-form/location-inputs'); ?>
    <?php echo get_template_part('template-parts/events/event-form/taxonomy-inputs'); ?>
    <?php echo get_template_part('template-parts/events/event-form/details-input'); ?>
    <?php echo get_template_part('template-parts/events/event-form/compensation-inputs'); ?>

    <div class="flex gap-2 mt-8">
        <button type="submit" class="bg-yellow hover:bg-navy text-black hover:text-white px-3 py-2 rounded-sm font-sun-motter text-14 w-fit">
            <span id="submit-button-content">
                <span class="htmx-indicator-component-block-replace">Update Event</span>
                <span class="htmx-indicator-component-block mx-2 my-1">
                    <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
                </span>
            </span>
        </button>
        <button type="button" x-on:click="showEditForm = false" class="bg-white hover:bg-black/10 text-black px-3 py-2 rounded-sm font-sun-motter text-14 w-fit border border-black/20">
            Cancel
        </button>
    </div>
</form>
<span id="event-update-result"></span>
