<?php $container_id = 'share-access-list-' . esc_attr($args['subject_id']); ?>
<div id="<?php echo $container_id; ?>" hx-swap-oob="true">
    <?php if (empty($args['entries'])) { ?>
        <p class="text-14 text-black/60 mt-4">No one has access yet.</p>
    <?php } else { ?>

        <ul class="mt-4 border border-black/10 rounded-sm divide-y divide-black/10 max-h-48 overflow-y-auto">
            <?php foreach ($args['entries'] as $entry) { ?>

                <li class="flex items-center justify-between gap-4 p-3 text-14">
                    <span class="flex-1 min-w-0 truncate">
                        <?php echo esc_html($entry->display_name ?: $entry->user_email); ?>
                        <span class="text-black/50">(<?php echo esc_html($entry->user_email); ?>)</span>
                    </span>

                    <?php if ($entry->access_type === HM_ACCESS_TYPE_OWNER) { ?>
                        <span class="text-black/50 uppercase shrink-0 p-3">Owner</span>
                    <?php } else {
                        $other_type = $entry->access_type === HM_ACCESS_TYPE_VIEW ? HM_ACCESS_TYPE_EDIT : HM_ACCESS_TYPE_VIEW;
                        $hx_vals    = [
                            'email'        => $entry->user_email,
                            'subject_id'   => (string) $args['subject_id'],
                            'subject_type' => (string) $args['subject_type'],
                        ]; ?>
                        <div hx-post="<?php echo esc_url(site_url('/wp-html/v1/access/')); ?>"
                            hx-vals='<?php echo esc_attr(wp_json_encode(array_merge($hx_vals, ['access_type' => HM_ACCESS_TYPE_VIEW]))); ?>'
                            hx-target="#access-list-results-<?php echo esc_attr($args['subject_id']); ?>"
                            hx-swap="innerHTML"
                            hx-trigger="access-view-<?php echo (string) $entry->id; ?> from:body"
                            hx-indicator="#<?php echo $container_id; ?>"
                            hidden></div>
                        <div hx-post="<?php echo esc_url(site_url('/wp-html/v1/access/')); ?>"
                            hx-vals='<?php echo esc_attr(wp_json_encode(array_merge($hx_vals, ['access_type' => HM_ACCESS_TYPE_EDIT]))); ?>'
                            hx-target="#access-list-results-<?php echo esc_attr($args['subject_id']); ?>"
                            hx-swap="innerHTML"
                            hx-trigger="access-edit-<?php echo (string) $entry->id; ?> from:body"
                            hx-indicator="#<?php echo $container_id; ?>"
                            hidden></div>
                        <div hx-delete="<?php echo esc_url(site_url('/wp-html/v1/access/' . $entry->id)); ?>"
                            hx-target="#access-list-results-<?php echo esc_attr($args['subject_id']); ?>"
                            hx-swap="innerHTML"
                            hx-trigger="access-revoke-<?php echo (string) $entry->id; ?> from:body"
                            hx-indicator="#<?php echo $container_id; ?>"
                            hidden></div>

                        <select class="border border-black/20 rounded-sm bg-white text-14 py-1 px-2 max-w-[80px]"
                            x-on:change="$dispatch('access-' + $el.value + '-<?php echo (string) $entry->id; ?>')"
                        >
                            <option value="<?php echo esc_attr($entry->access_type); ?>" selected><?php echo esc_html(ucfirst($entry->access_type)); ?></option>
                            <option value="<?php echo esc_attr($other_type); ?>"><?php echo esc_html(ucfirst($other_type)); ?></option>
                            <option value="revoke">Revoke</option>
                        </select>
                    <?php } ?>

                </li>

            <?php } ?>
        </ul>

    <?php } ?>
</div>
