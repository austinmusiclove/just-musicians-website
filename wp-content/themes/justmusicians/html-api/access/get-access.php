<?php

$subject_id  = sanitize_text_field(wp_unslash($_GET['subject_id'] ?? ''));
$access_type = sanitize_key(wp_unslash($_GET['access_type'] ?? ''));

// Authorize
$auth = require_subject_owner($subject_id);
if (!$auth) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'You are not authorized to view access for this subject' })"></span>
    <?php exit;
}

if ($access_type !== '' && !in_array($access_type, [HM_VIEW_TYPE_MGMT, HM_EDIT_TYPE_MGMT], true)) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'Invalid access type' })"></span>
    <?php exit;
}

status_header(200);
get_template_part('template-parts/access/access-list', '', [
    'entries' => hm_get_access_entries($subject_id, $access_type !== '' ? $access_type : null),
]);
exit;
