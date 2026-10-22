<div class="flex flex-col gap-2">
    <h3 class="font-bold text-22 mb-2">Events</h3>
    <p class="text-16">By associating events with your applciation, musicians can submit their availability for each event when filling out the application.</p>
    <?php if (!empty($args['upcoming_events'])) { ?>
    <div class="flex flex-col gap-2">
        <input type="hidden" name="events[]"> <!-- This is here so that if no events are selected, it will result in update instead of having the events[] arg ignored -->
        <?php foreach ($args['upcoming_events'] as $event) { ?>
        <label class="custom-checkbox flex items-center gap-3 cursor-pointer p-2 border border-black/20 rounded-sm hover:bg-yellow-light">
        <input type="checkbox" name="events[]" value="<?php echo $event['post_id']; ?>" <?php if (isset($args['x-model'])) { ?>x-model="<?php echo $args['x-model']; ?>"<?php } ?>>
            <span class="checkmark" style="top: 50%; transform: translateY(-50%); left: .5rem!important"></span>
            <div class="flex flex-col">
                <div class="text-14 font-semibold"><?php echo esc_html($event['event_name']); ?></div>
                <div class="text-12 text-black/60"><?php echo $event['start_date'] ? gmdate('M j, Y', strtotime($event['start_date'])) : ''; ?></div>
            </div>
        </label>
        <?php } ?>
    </div>
    <?php } else { ?>
    <p class="text-16 text-black/50">You don't have any upcoming events. <a class="underline" href="<?php echo site_url('event-form'); ?>">Create an event</a> first to add it to this application.</p>
    <?php } ?>
</div>
