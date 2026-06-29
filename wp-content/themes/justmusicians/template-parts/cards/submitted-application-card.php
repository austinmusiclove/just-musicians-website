<div class="py-4 relative flex flex-row items-start gap-3 md:gap-6 relative border-b border-black/20"
    <?php if (!empty($args['last']) && empty($args['is_last_page'])) { ?>
        hx-get="<?php echo site_url('/wp-html/v1/application-submissions/?page=' . $args['next_page']); ?>"
        hx-trigger="revealed once"
        hx-swap="beforeend"
        hx-target="#results"
        hx-indicator="#submissions-spinner-bottom"
        hx-include="#submitted-applications-form"
    <?php } ?>
    x-data="{
        showForm: false,
        message:     '<?php echo clean_str_for_doublequotes($args['submission']['message']); ?>',
        status:      '<?php echo $args['submission']['status']; ?>',
        statusInput: '<?php echo $args['submission']['status']; ?>',
        updated:     '<?php echo $args['submission']['updated']; ?>',
        _updateSubmission(message, status, updated) { this.showForm = false; this.message = message; this.status = status; this.updated = updated },
    }"
    x-on:update-submission="_updateSubmission($event.detail.message, $event.detail.status, $event.detail.updated);"
>

    <div class="py-2 flex flex-col gap-y-2 flex-1 min-w-0 w-full">

        <div class="flex flex-row items-start justify-between gap-2">
            <h2 class="text-18 sm:text-20 font-semibold"><?php echo esc_html($args['submission']['application_title']); ?></h2>
            <span class="text-12 px-2 py-0.5 rounded-full font-semibold capitalize whitespace-nowrap"
                :class="status === 'active' ? 'bg-navy text-white' : 'bg-yellow/40'"
                x-text="status"
            ></span>
        </div>

        <div class="flex items-center gap-2">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full overflow-hidden bg-yellow-light shrink-0">
                    <?php if ($args['submission']['listing_thumbnail_url']) { ?>
                        <img src="<?php echo esc_url($args['submission']['listing_thumbnail_url']); ?>" alt="<?php echo esc_attr($args['submission']['listing_name']); ?>" class="w-full h-full object-cover" />
                    <?php } else { ?>
                        <div class="w-full h-full flex items-center justify-center text-12 font-bold text-black/40"><?php echo strtoupper(mb_substr($args['submission']['listing_name'], 0, 1)); ?></div>
                    <?php } ?>
                </div>
                <div class="flex flex-col">
                    <span class="text-14 font-semibold"><?php echo esc_html($args['submission']['listing_name']); ?></span>
                    <p class="text-12 text-black/50">Last updated <span x-text="updated"></span></p>
                </div>
            </div>
        </div>

        <div x-show="!showForm" x-cloak>
            <p class="text-14 text-black/60" x-show="message" x-text="message"></p>
        </div>

        <!-- Respond Button and form -->
        <?php get_template_part('template-parts/cards/card-components/submitted-application-card-form', '', [
            'app_submission_id' => $args['submission']['post_id'],
        ]); ?>


    </div>

</div>
