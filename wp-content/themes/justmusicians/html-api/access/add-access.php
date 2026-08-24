<?php

$subject_id   = sanitize_text_field(wp_unslash($_POST['subject_id'] ?? ''));
$access_type  = sanitize_key(wp_unslash($_POST['access_type'] ?? ''));
$subject_type = sanitize_key(wp_unslash($_POST['subject_type'] ?? ''));
if (!$subject_type) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'Missing subject type' })"></span>
    <?php exit;
}

// Authorize
$auth = require_subject_owner($subject_id);
if (!$auth) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'You are not authorized to manage access for this subject' })"></span>
    <?php exit;
}

$email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
if (!$email || !is_email($email)) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'Please enter a valid email address' })"></span>
    <?php exit;
}

$user = get_user_by('email', $email);
if (!$user) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'No user found with that email address' })"></span>
    <?php exit;
}

if (!in_array($access_type, [HM_VIEW_TYPE_MGMT, HM_EDIT_TYPE_MGMT], true)) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'Invalid access type' })"></span>
    <?php exit;
}

// Grant access
$result = hm_grant_access($user->ID, $subject_id, $access_type, $subject_type);
if (!$result) { ?>
    <span x-init="$dispatch('error-toast', { 'message': 'Error granting access' })"></span>
    <?php exit;
} ?>
<span x-init="$dispatch('success-toast', { 'message': 'Access granted to <?php echo clean_str_for_doublequotes($user->user_email); ?>' })"></span>

<?php
get_template_part('template-parts/access/access-list', '', [
    'entries'      => hm_get_access_entries($subject_id),
    'subject_id'   => $subject_id,
    'subject_type' => $subject_type,
]);
exit;
