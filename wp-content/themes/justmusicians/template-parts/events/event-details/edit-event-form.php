<?php
$post_id = $args['post_id'] ?? 0;
?>
<form
    hx-post="<?php echo site_url('/wp-html/v1/events/' . $post_id); ?>"
    hx-target="#event-update-result"
    hx-indicator="#submit-button-content"
>
    <h3 class="font-bold text-16 mb-2">Details</h3>
    <textarea class="w-full h-24 sm:h-40" name="event_details" placeholder="Event details" :value="details"></textarea>

    <div class="flex gap-2 mt-4">
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
