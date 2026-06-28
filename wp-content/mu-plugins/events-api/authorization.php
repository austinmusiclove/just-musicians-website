<?php

function user_is_event_author($event_id) {

    if (!isset($event_id) || !is_numeric($event_id)) {
        return new WP_Error('invalid_event_id', 'Event ID is required and must be an integer.', ['status' => 400]);
    }

    $user_id = get_current_user_id();
    $author_id = get_post_field('post_author', $event_id);

    if ($user_id != $author_id) {
        return new WP_Error('unauthorized_user', 'Your account is not authorized for this resource', ['status' => 400]);
    }

    return true;
}

function require_event_authorship($event_id) {

    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must sign in to perform this function.');
    }

    if (current_user_can('manage_options')) {
        return true;
    }

    return user_is_event_author($event_id);
}

function user_can_view_single_event($event_id)   { return require_event_authorship($event_id); }
function user_can_delete_event($event_id)        { return require_event_authorship($event_id); }
function user_can_update_event($event_id)        { return require_event_authorship($event_id); }
function user_can_request_proposal($event_id)    { return require_event_authorship($event_id); }
