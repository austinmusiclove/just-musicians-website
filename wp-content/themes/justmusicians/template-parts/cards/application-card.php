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

    <div class="py-2 flex flex-col items-start gap-4 flex-1 min-w-0 w-full">

        <!-- Application Title -->
        <div class="flex flex-row items-start justify-between gap-2">
            <a href="<?php echo esc_url($args['permalink']); ?>"><h2 class="text-18 sm:text-20 font-semibold"><?php echo esc_html($args['title']); ?></h2></a>
        </div>

        <!-- Application Link -->
        <div class="text-yellow">
            <?php echo get_template_part('template-parts/global/copy-to-clipboard', '', [
                'text' => get_musician_application_url($args['post_id']),
                'external_link' => esc_url(get_musician_application_url($args['post_id'])),
                'show_text' => true,
                'icon_size_classes' => 'h-6 sm:h-4',
            ]); ?>
        </div>

        <!-- Application Description -->
        <?php if ($args['description']) { ?>
            <div x-data="{ description: '<?php echo clean_str_for_doublequotes($args['description']); ?>' }">
                <?php get_template_part('template-parts/cards/card-components/show-more-text', '', [
                    'text_var' => 'description',
                    'limit'    => 200,
                ]); ?>
            </div>
        <?php } ?>

        <!-- Buttons -->
        <div class="flex flex-wrap items-center gap-3">
            <a class="border border-black/20 hover:border-black px-3 py-2 rounded-sm font-sun-motter text-14 transition-colors"
                href="<?php echo esc_url(add_query_arg('tab', 'applicants', $args['permalink'])); ?>"
            >Review Applicants (<?php echo $args['applicant_count'] ?? '0'; ?>)</a>
            <a class="bg-yellow hover:bg-navy text-black hover:text-white px-3 py-2 rounded-sm font-sun-motter text-14 transition-colors"
                href="<?php echo esc_url($args['permalink']); ?>"
            >Edit Application</a>
        </div>
    </div>

</div>
