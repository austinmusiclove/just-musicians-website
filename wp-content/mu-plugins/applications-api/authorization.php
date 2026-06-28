<?php

function user_owns_application($application_id) {

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

function require_application_authorization($application_id) {

    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must sign in to perform this function.');
    }

    if (current_user_can('manage_options')) {
        return true;
    }

    return user_owns_application($application_id);
}

function user_can_view_single_application($application_id)   { return require_application_authorization($application_id); }
function user_can_view_update_application($application_id)   { return require_application_authorization($application_id); }

function user_can_create_application() {
    global $wpdb;

    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must sign in to create an application.');
    }

    if (current_user_can('manage_options')) {
        return true;
    }

    // Check if user has an application already
    $query = $wpdb->prepare(
        "SELECT 1 FROM {$wpdb->posts} WHERE post_author = %d AND post_type = 'application' AND post_status = 'publish' LIMIT 1",
        get_current_user_id()
    );
    $has_application = (bool) $wpdb->get_var( $query );

    // Regular user is limited to 1 application; they can create an application if they don't have any yet
    if ($has_application) {
        return new WP_Error('unauthorized', 'You are limited to one application on your account.');
    }

    return true;;
}
