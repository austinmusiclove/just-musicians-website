<?php
$proposal = $args['proposal'];
$listing_name = get_post_meta($proposal['listing_id'], 'name', true);
?>

<div class="block bg-white border border-black/20 rounded-sm p-4 hover:bg-yellow-light/30 transition-colors"


    <?php if (!empty($args['last']) && empty($args['is_last_page'])) { ?>
        hx-get="<?php echo site_url('/wp-html/v1/my-gigs/?page=' . $args['next_page']); ?>"
        hx-trigger="revealed once"
        hx-indicator="#spinner-end"
        hx-swap="beforeend"
    <?php } ?>>


    <a href="<?php echo esc_url($proposal['event']['permalink']); ?>" >
        <div class="flex items-center justify-between">
            <div>
                <span class="font-semibold text-16"><?php echo esc_html($proposal['event']['event_name']); ?></span>
                <?php if ($listing_name) { ?>
                    <span class="text-14 text-black/50 ml-2">via <?php echo esc_html($listing_name); ?></span>
                <?php } ?>
            </div>
            <span class="text-12 px-2 py-0.5 rounded-full bg-yellow/30 text-12 capitalize shrink-0"><?php echo esc_html($proposal['status']); ?></span>
        </div>
    </a>


</div>
