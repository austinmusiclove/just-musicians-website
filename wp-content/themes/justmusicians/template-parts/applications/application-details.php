<!-- Details -->
<div class="flex flex-col gap-4" x-show="!showEditForm" x-cloak>

    <!-- Application URL -->
    <div>
        <div class="flex items-center gap-4">
            <h3 class="font-bold text-16">Public Application URL</h3>
            <?php echo get_template_part('template-parts/global/copy-to-clipboard', '', [
                'text'          => get_musician_application_url(get_the_ID()),
                'external_link' => esc_url(get_musician_application_url(get_the_ID())),
                'show_text'     => false,
                'icon_size_classes' => 'h-6 sm:h-4',
            ]); ?>
        </div>
        <span class="text-14">
            <?php echo esc_url(get_musician_application_url(get_the_ID())); ?>
        </span>
    </div>

    <!-- Description -->
    <div>
        <h3 class="font-bold text-16 mb-2">Description</h3>
        <div class="text-16" :class="description ? '' : 'text-black/50'" x-html="description ? description : 'No description provided'"></div>
    </div>

    <!-- Events -->
    <div>
        <h3 class="font-bold text-16 mb-2">Events</h3>
        <div class="flex flex-col gap-2">
            <template x-for="event in appEvents" :key="event.event_id">
                <div class="p-2 border border-black/20 rounded-sm">
                    <div class="flex flex-col">
                        <span class="text-14 font-semibold" x-text="event.event_name"></span>
                        <span class="text-12 text-black/60" x-text="event.start_date ? new Date(event.start_date + 'T00:00:00').toLocaleDateString('en', { month: 'short', day: 'numeric', year: 'numeric' }) : ''"></span>
                    </div>
                </div>
            </template>
        </div>
        <div class="text-16 text-black/50" x-show="appEvents.length === 0" x-cloak>No events associated with this application</div>
    </div>

    <!-- Actions -->
    <div class="pt-4 flex gap-2">
        <button type="button" x-on:click="showEditForm = true" class="bg-yellow hover:bg-navy text-black hover:text-white px-3 py-2 rounded-sm font-sun-motter text-14 w-fit whitespace-nowrap inline-block">
            Edit Application
        </button>
        <button type="button" class="bg-white hover:bg-red hover:text-white border border-black/20 hover:border-red px-3 py-2 rounded-sm font-sun-motter text-14 w-fit whitespace-nowrap inline-block"
            hx-delete="<?php echo site_url('/wp-html/v1/applications/' . get_the_ID()); ?>"
            hx-confirm="Are you sure you want to delete this application?"
            hx-target="#delete-result"
        >
            Delete Application
            <span id="delete-result"></span>
        </button>
    </div>

</div>

<!-- Edit form -->
<div x-show="showEditForm">
    <?php echo get_template_part('template-parts/applications/edit-application-form', '', [
        'description'        => $args['description'],
        'upcoming_events'    => $args['upcoming_events'],
    ]); ?>
</div>
