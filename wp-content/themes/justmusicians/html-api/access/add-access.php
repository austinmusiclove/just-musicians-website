<?php

$subject_id   = sanitize_text_field(wp_unslash($_POST['subject_id'] ?? ''));
$access_type  = sanitize_key(wp_unslash($_POST['access_type'] ?? ''));
$subject_type = sanitize_key(wp_unslash($_POST['subject_type'] ?? ''));
if (!$subject_type) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'Missing subject type' })"></span>
    <?php exit;
}

// Authorize
$auth = require_subject_owner($subject_id, $subject_type);
if (!$auth) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'You are not authorized to manage access for this subject' })"></span>
    <?php exit;
}

$email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
if (!$email || !is_email($email)) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'Please enter a valid email address' })"></span>
    <?php exit;
}

if (!in_array($access_type, [HM_ACCESS_TYPE_VIEW, HM_ACCESS_TYPE_EDIT], true)) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'Invalid access type' })"></span>
    <?php exit;
}

$grant = hm_grant_access_by_email($email, $subject_id, $subject_type, $access_type);
if (!$grant['result']) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'Error granting access' })"></span>
    <?php exit;
}

if ($grant['type'] === 'user') {
    if ($grant['is_new']) {
        send_access_granted_email($grant['user']->user_email, $subject_id, $subject_type, $access_type);
    }
    $success_message = 'Access granted to ' . $grant['user']->user_email;
} else {
    if ($grant['is_new']) {
        send_access_invite_email($email, $subject_id, $subject_type, $access_type);
    }
    $success_message = 'Access invite sent to ' . $email;
} ?>
<span x-init="$dispatch('success-toast', { 'message': '<?php echo clean_str_for_doublequotes($success_message); ?>' })"></span>

<?php
get_template_part('template-parts/access/access-list', '', [
    'entries'      => hm_get_access_entries($subject_id),
    'subject_id'   => $subject_id,
    'subject_type' => $subject_type,
]);
exit;
