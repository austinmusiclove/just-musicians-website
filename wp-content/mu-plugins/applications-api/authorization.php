<?php

function user_is_application_author($application_id) {

    if (!isset($application_id) || !is_numeric($application_id)) {
        return new WP_Error('invalid_application_id', 'Application ID is required and must be an integer.', ['status' => 400]);
    }

    $user_id = get_current_user_id();
    $author_id = get_post_field('post_author', $application_id);

    if ($user_id != $author_id) {
        return new WP_Error('unauthorized_user', 'Your account is not authorized for this resource', ['status' => 400]);
    }

    return true;
}

function require_application_authorship($application_id) {

    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must sign in to perform this function.');
    }

    if (current_user_can('manage_options')) {
        return true;
    }

    return user_is_application_author($application_id);
}

function require_application_access($application_id, $allowed_access_types) {

    if (!isset($application_id) || !is_numeric($application_id)) {
        return new WP_Error('invalid_application_id', 'Application ID is required and must be an integer.', ['status' => 400]);
    }

    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must sign in to perform this function.');
    }

    if (current_user_can('manage_options')) {
        return true;
    }

    $user_id = get_current_user_id();

    foreach ($allowed_access_types as $access_type) {
        if (hm_user_has_access($user_id, $application_id, $access_type, 'application')) {
            return true;
        }
    }

    return new WP_Error('unauthorized_user', 'Your account is not authorized for this resource', ['status' => 400]);
}

function user_can_view_single_application($application_id)  { return require_application_access($application_id, [HM_ACCESS_TYPE_VIEW, HM_ACCESS_TYPE_EDIT, HM_ACCESS_TYPE_OWNER]); }
function user_can_update_application($application_id)       { return require_application_access($application_id, [HM_ACCESS_TYPE_EDIT, HM_ACCESS_TYPE_OWNER]); }
function user_can_delete_application($application_id)       { return require_application_access($application_id, [HM_ACCESS_TYPE_OWNER]); }

function user_can_create_application() {
    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must sign in to create an application.');
    }

    if (current_user_can('manage_options')) {
        return true;
    }

    $user_id = get_current_user_id();
    $owned_app_ids = hm_get_subject_ids_for_user($user_id, HM_ACCESS_TYPE_OWNER, 'application');

    if (empty($owned_app_ids)) {
        return true;
    }

    if (current_user_can('hm_pro_buyer')) {
        return true;
    }

    return new WP_Error('unauthorized', 'You are limited to one application. Upgrade to Pro for unlimited applications.');
}

function user_can_update_application_submission($submission_id) {

    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must sign in to perform this function.');
    }

    if (current_user_can('manage_options')) {
        return true;
    }

    if (!isset($submission_id) || !is_numeric($submission_id)) {
        return new WP_Error('invalid_submission_id', 'Submission ID is required and must be an integer.', ['status' => 400]);
    }

    $user_id   = get_current_user_id();
    $author_id = (int) get_post_field('post_author', $submission_id);

    if ($user_id !== $author_id) {
        return new WP_Error('unauthorized_user', 'Your account is not authorized for this resource.', ['status' => 400]);
    }

    return true;
}

