<div class="py-4 relative flex flex-row items-start gap-3 md:gap-6 relative border-b border-black/20"
    <?php if (!empty($args['last']) && empty($args['is_last_page'])) { ?>
        hx-get="<?php echo site_url('/wp-html/v1/applications/?page=' . $args['next_page']); ?>"
        hx-trigger="revealed once"
        hx-swap="beforeend"
        hx-target="#results"
        hx-indicator="#applications-spinner"
        hx-include="#applications-form"
    <?php } ?>
>

    <div class="py-2 flex flex-col gap-y-2 flex-1 min-w-0 w-full">
        <div class="flex flex-row items-start justify-between gap-2">
            <a href="<?php echo esc_url($args['permalink']); ?>"><h2 class="text-18 sm:text-20 font-semibold"><?php echo esc_html($args['title']); ?></h2></a>
        </div>

        <?php if ($args['description']) { ?>
            <p class="text-14 text-black/60"><?php echo esc_html($args['description']); ?></p>
        <?php } ?>

        <?php echo get_template_part('template-parts/global/copy-to-clipboard', '', [
            'text' => get_musician_application_url($args['post_id']),
        ]); ?>
    </div>

</div>
