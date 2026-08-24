<?php

/**
 * Whether the current user is considered the owner of a subject.
 * Admins and users with an 'owner' entry for the subject are allowed.
 * Extensible via the 'hm_is_subject_owner' filter.
 */
function require_subject_owner($subject_id) {
    $user = wp_get_current_user();

    $authorized = $user->exists() && (
        user_can($user->ID, 'manage_options')
        || hm_user_has_access($user->ID, (string) $subject_id, HM_ACCESS_TYPE_OWNER)
    );

    return apply_filters('hm_is_subject_owner', $authorized, $subject_id);
}
