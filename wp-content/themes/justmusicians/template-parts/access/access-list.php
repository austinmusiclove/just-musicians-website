<div id="share-access-list">
    <?php if (empty($args['entries'])) : ?>
        <p class="text-14 text-black/60 mt-4">No one has access yet.</p>
    <?php else : ?>

        <ul class="mt-4 border border-black/10 rounded-sm divide-y divide-black/10 max-h-48 overflow-y-auto">
            <?php foreach ($args['entries'] as $entry) : ?>


                <li class="flex items-center justify-between gap-4 p-3 text-14">
                    <span class="truncate">
                        <?php echo esc_html($entry->display_name ?: $entry->user_email); ?>
                        <span class="text-black/50">(<?php echo esc_html($entry->user_email); ?>)</span>
                    </span>
                    <span class="flex items-center gap-3 shrink-0">
                        <span class="text-black/50 uppercase"><?php echo esc_html($entry->access_type); ?></span>
                        <button type="button"
                            class="text-red hover:underline font-sun-motter"
                            hx-delete="<?php echo esc_url(site_url('/wp-html/v1/access/' . $entry->id)); ?>"
                            hx-target="#share-access-list"
                            hx-swap="outerHTML"
                            hx-confirm="Revoke access for <?php echo esc_attr($entry->user_email); ?>?"
                        >Remove</button>
                    </span>
                </li>


            <?php endforeach; ?>
        </ul>

    <?php endif; ?>
</div>
