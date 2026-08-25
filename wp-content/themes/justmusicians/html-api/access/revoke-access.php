<?php

$access_id = absint(get_query_var('access-id'));

$entry = $access_id ? hm_get_access_by_id($access_id) : null;
if (!$entry) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'Access entry not found' })"></span>
    <?php exit;
}

// Authorize against the entry's subject
$auth = require_subject_owner($entry->subject_id, $entry->subject_type);
if (!$auth) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'You are not authorized to manage access for this subject' })"></span>
    <?php exit;
}

$result = hm_revoke_access_by_id($access_id);
if (!$result) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'Error revoking access' })"></span>
    <?php exit;
} ?>

<span x-init="$dispatch('success-toast', { 'message': 'Access revoked' })"></span>
<?php get_template_part('template-parts/access/access-list', '', [
    'entries'      => hm_get_access_entries($entry->subject_id),
    'subject_id'   => $entry->subject_id,
    'subject_type' => $entry->subject_type,
]);
exit;
