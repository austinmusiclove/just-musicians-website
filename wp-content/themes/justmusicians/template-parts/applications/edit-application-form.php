<form
    x-bind:hx-post="'<?php echo site_url('/wp-html/v1/applications/'); ?>' + applicationId"
    hx-target="#application-update-result"
    hx-indicator="#update-btn-content"
>

    <!-- Title -->
    <h3 class="font-bold text-16 mb-1 mt-6">Application Title</h3>
    <input type="text" name="title" class="w-full px-3 py-2 border border-black/20 rounded-sm text-14" required
        x-bind:value="title"
    />

    <!-- Description -->
    <h3 class="font-bold text-16 mb-1 mt-6">Description</h3>
    <?php
    wp_editor($args['description'], 'application_description', [
        'textarea_name' => 'description',
        'textarea_rows' => 8,
        'media_buttons' => false,
        'teeny'         => true,
        'quicktags'     => false,
        'toolbar1'      => 'bold,italic,underline,bullist,numlist,link,unlink',
        'toolbar2'      => '',
    ]);
    ?>

    <!-- Events -->
    <h3 class="font-bold text-16 mb-1 mt-6">Events</h3>
    <p class="text-16 mb-2">By associating events with your application, musicians can submit their availability for each event when filling out the application.</p>
    <?php if (!empty($args['upcoming_events'])) { ?>
    <div class="flex flex-col gap-2">
        <input type="hidden" name="events[]" > <!-- This is here so that if no events are selected, it will result in update instead of having the events[] arg ignored -->
        <?php foreach ($args['upcoming_events'] as $event) { ?>
        <label class="flex items-center gap-3 cursor-pointer p-2 border border-black/20 rounded-sm hover:bg-yellow-light">
            <input type="checkbox" name="events[]" value="<?php echo $event['post_id']; ?>" x-model="selectedEventIds">
            <div class="flex flex-col">
                <span class="text-14 font-semibold"><?php echo esc_html($event['event_name']); ?></span>
                <span class="text-12 text-black/60"><?php echo $event['start_date'] ? gmdate('M j, Y', strtotime($event['start_date'])) : ''; ?></span>
            </div>
        </label>
        <?php } ?>
    </div>
    <?php } else { ?>
    <p class="text-16 text-black/50">You don't have any upcoming events. <a class="underline" href="<?php echo site_url('event-form'); ?>">Create an event</a> first to add it to this application.</p>
    <?php } ?>

    <!-- Buttons -->
    <div class="flex items-center gap-2 mt-8">

        <button type="submit" class="hover:bg-yellow-light bg-yellow font-sun-motter px-3 py-2 rounded-sm text-14">
            <span id="update-btn-content">
                <span class="htmx-indicator-component-block-replace">Update Application</span>
                <span class="htmx-indicator-component-block mx-2 my-1">
                    <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
                </span>
            </span>
        </button>

        <button type="button" class="bg-white hover:bg-black/10 text-black px-3 py-2 rounded-sm font-sun-motter text-14 w-fit border border-black/20"
            x-on:click="showEditForm = false"
        >Cancel</button>

    </div>
</form>
<span id="application-update-result"></span>
