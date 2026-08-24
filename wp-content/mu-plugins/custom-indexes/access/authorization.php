<?php

/**
 * Whether the current user may manage access entries for a subject.
 * Admins and users with an 'access_mgmt' entry for the subject are allowed.
 * Extensible via the 'hm_can_manage_subject_access' filter.
 */
function require_subject_access_management($subject_id) {
    $user = wp_get_current_user();

    $authorized = $user->exists() && (
        user_can($user->ID, 'manage_options')
        || hm_user_has_access($user->ID, (string) $subject_id, HM_ACCESS_TYPE_MGMT)
    );

    return apply_filters('hm_can_manage_subject_access', $authorized, $subject_id);
}
