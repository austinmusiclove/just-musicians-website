<div class="flex justify-start pt-2">
    <button type="button" x-on:click="showForm = true" x-show="!showForm" x-cloak
        class="bg-yellow hover:bg-navy text-black hover:text-white px-3 py-2 rounded-sm font-sun-motter text-14 w-fit whitespace-nowrap"
    >Update Application</button>
</div>

<form class="w-full" x-show="showForm" x-cloak
    hx-post="<?php echo site_url('/wp-html/v1/application-submissions/' . $args['app_submission_id'] . '/'); ?>"
    hx-target="#submission-result-<?php echo $args['app_submission_id']; ?>"
    hx-swap="innerHTML"
    hx-indicator="#submit-submission-button-content-<?php echo $args['app_submission_id']; ?>"
>
    <div class="flex flex-col gap-2">
        <textarea name="message" placeholder="Your message..." rows="3" class="border border-black/20 rounded-sm p-2 text-14 w-full" x-model="message"></textarea>

        <div>
            <span class="text-12 text-black/50 font-semibold">Status</span>
            <div class="flex gap-2 mt-1">
                <label class="cursor-pointer px-3 py-1 rounded-full border border-black/20 text-14 active:bg-navy active:text-white hover:bg-navy-light hover:text-black"
                    :class="statusInput == 'active' ? 'bg-navy text-white' : 'bg-transparent text-black'">
                    <input type="radio" name="status" value="active" class="sr-only" x-model="statusInput" required>Active
                </label>
                <label class="cursor-pointer px-3 py-1 rounded-full border border-black/20 text-14 active:bg-navy active:text-white hover:bg-navy-light hover:text-black"
                    :class="statusInput == 'withdrawn' ? 'bg-navy text-white' : 'bg-transparent text-black'">
                    <input type="radio" name="status" value="withdrawn" class="sr-only" x-model="statusInput" required>Withdrawn
                </label>
            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-yellow hover:bg-navy text-black hover:text-white px-3 py-2 rounded-sm font-sun-motter text-14 w-fit">
                <span id="submit-submission-button-content-<?php echo $args['app_submission_id']; ?>">
                    <span class="htmx-indicator-component-block-replace">Submit</span>
                    <span class="htmx-indicator-component-block mx-2 my-1">
                        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
                    </span>
                </span>
            </button>
            <button type="button" x-on:click="showForm = false" class="bg-white hover:bg-black/10 text-black px-3 py-2 rounded-sm font-sun-motter text-14 w-fit border border-black/20">
                Cancel
            </button>
        </div>
    </div>
    <div id="submission-result-<?php echo $args['app_submission_id']; ?>"></div>
</form>
