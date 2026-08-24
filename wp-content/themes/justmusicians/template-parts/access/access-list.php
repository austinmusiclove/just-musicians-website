<?php $container_id = 'share-access-list-' . esc_attr($args['subject_id']); ?>
<div id="<?php echo $container_id; ?>" hx-swap-oob="true">
    <?php if (empty($args['entries'])) { ?>
        <p class="text-14 text-black/60 mt-4">No one has access yet.</p>
    <?php } else { ?>

        <ul class="mt-4 border border-black/10 rounded-sm divide-y divide-black/10 max-h-48 overflow-y-auto">
            <?php foreach ($args['entries'] as $entry) {

                $delete_url  = esc_url(site_url('/wp-html/v1/access/' . $entry->id));
                $post_url    = esc_url(site_url('/wp-html/v1/access/'));
                $email_js    = esc_js($entry->user_email);
                $subject_js  = esc_js((string) $args['subject_id']);
                $stype_js    = esc_js((string) $args['subject_type']);
                $current_js  = esc_js($entry->access_type);
                $other_type  = $entry->access_type === HM_VIEW_TYPE_MGMT ? HM_EDIT_TYPE_MGMT : HM_VIEW_TYPE_MGMT;
            ?>


                <li class="flex items-center justify-between gap-4 p-3 text-14">
                    <span class="truncate">
                        <?php echo esc_html($entry->display_name ?: $entry->user_email); ?>
                        <span class="text-black/50">(<?php echo esc_html($entry->user_email); ?>)</span>
                    </span>

                    <?php if ($entry->access_type === HM_ACCESS_TYPE_OWNER) { ?>
                        <span class="text-black/50 uppercase shrink-0 p-3">Owner</span>
                    <?php } else { ?>
                        <select class="border border-black/20 rounded-sm bg-white text-14 py-1 px-2 max-w-[80px]"
                            x-on:change="<?php echo esc_attr("if (\$el.value === 'revoke') { htmx.ajax('DELETE', '{$delete_url}', { target: '#{$container_id}', swap: 'outerHTML' }); } else if (\$el.value !== '{$current_js}') { htmx.ajax('POST', '{$post_url}', { target: '#{$container_id}', swap: 'outerHTML', values: { email: '{$email_js}', subject_id: '{$subject_js}', subject_type: '{$stype_js}', access_type: \$el.value } }); }"); ?>"
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
