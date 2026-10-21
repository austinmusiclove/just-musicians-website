<div class="flex justify-start pt-2 gap-2">
    <a href="<?php echo esc_url($args['application_url'] ?? ''); ?>" target="_blank" rel="noopener"
        class="bg-yellow hover:bg-navy text-black hover:text-white px-3 py-2 rounded-sm font-sun-motter text-14 w-fit whitespace-nowrap"
    >Resubmit Application</a>
    <button type="button" x-show="status != 'withdrawn'" x-cloak
        hx-post="<?php echo site_url('/wp-html/v1/application-submissions/' . $args['app_submission_id'] . '/'); ?>"
        hx-vals='{"status": "withdrawn"}'
        hx-target="#submission-result-<?php echo $args['app_submission_id']; ?>"
        hx-swap="innerHTML"
        hx-indicator="#withdraw-submission-button-content-<?php echo $args['app_submission_id']; ?>"
        class="border border-black/20 text-black/60 hover:text-black/80 hover:border-black/40 px-3 py-2 rounded-sm font-sun-motter text-14 w-fit whitespace-nowrap"
    >
        <span id="withdraw-submission-button-content-<?php echo $args['app_submission_id']; ?>">
            <span class="htmx-indicator-component-block-replace">Withdraw Application</span>
            <span class="htmx-indicator-component-block mx-2 my-1">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'black']); ?>
            </span>
        </span>
    </button>
</div>

<div id="submission-result-<?php echo $args['app_submission_id']; ?>"></div>
